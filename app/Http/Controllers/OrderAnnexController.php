<?php

namespace App\Http\Controllers;

use App\Models\OrderAnnex;
use App\Models\OrderAnnexImage;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderAnnexController extends Controller
{
    /** Consultar evidencia de una orden. */
    public function show($orderId)
    {
        $annex = OrderAnnex::with('images')
            ->where('order_id', $orderId)
            ->first();

        if (!$annex) {
            return response()->json([
                'data' => null,
                'images' => [],
            ]);
        }

        $annex->images->transform(function ($image) {
            $image->url = Storage::disk('public')->url($image->image);
            return $image;
        });

        return response()->json(['data' => $annex,]);
    }

    /**  Guardar evidencia como borrador. */
    public function save(Request $request, $order)
    {
        $request->validate([
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'imagenes_eliminar' => ['nullable', 'array'],
            'imagenes_eliminar.*' => ['integer', 'exists:order_annex_images,id'],
        ]);

        $order = Orders::findOrFail($order);

        $annex = OrderAnnex::firstOrCreate(
            ['order_id' => $order->id],
            ['status' => 0]
        );

        if ((int) $annex->status === 1) {
            return response()->json(['message' => 'La evidencia ya fue finalizada y no puede modificarse.'], 422);
        }

        DB::beginTransaction();

        try {
            $disk = Storage::disk('public');

            // 1. ELIMINAR IMÁGENES MARCADAS
            $idsEliminar = $request->input('imagenes_eliminar', []);

            if (!empty($idsEliminar)) {
                $imagesToDelete = OrderAnnexImage::where('order_annex_id', $annex->id)
                    ->whereIn('id', $idsEliminar)
                    ->get();

                foreach ($imagesToDelete as $image) {
                    if ($image->image && $disk->exists($image->image)) {
                        $disk->delete($image->image);
                    }
                    $image->delete();
                }
            }

            // 2. CONTAR LAS QUE QUEDARON
            $currentCount = OrderAnnexImage::where('order_annex_id', $annex->id)->count();

            // 3. CONTAR NUEVAS
            $newImages = $request->file('images', []);
            $newCount = count($newImages);

            if (($currentCount + $newCount) > 6) {
                DB::rollBack();

                return response()->json(['message' => "La evidencia no puede tener más de 6 imágenes. Actualmente tienes {$currentCount}."], 422);
            }

            // 4. GUARDAR NUEVAS IMÁGENES   storage/app/public/anexos/tmp/{order}
            $directory = "anexos/tmp/{$order->id}";

            $lastOrder = (int) OrderAnnexImage::where('order_annex_id', $annex->id)->max('orden');

            foreach ($newImages as $index => $file) {
                $orden = $lastOrder + $index + 1;

                $filename = 'evidencia_' . $order->id . '_' . time() . '_' .
                    uniqid() . '.' . $file->getClientOriginalExtension();

                $path = $file->storeAs($directory, $filename, 'public');

                OrderAnnexImage::create([
                    'order_annex_id' => $annex->id,
                    'image' => $path,
                    'orden' => $orden,
                ]);
            }

            DB::commit();

            // 5. DEVOLVER EVIDENCIA ACTUALIZADA
            $images = OrderAnnexImage::where('order_annex_id', $annex->id)
                ->orderBy('orden')
                ->get()
                ->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'image' => $image->image,
                        'orden' => $image->orden,
                        'url' => Storage::disk('public')->url($image->image),
                    ];
                });

            return response()->json([
                'message' => 'Evidencia guardada correctamente.',
                'annex_id' => $annex->id,
                'status' => (int) $annex->status,
                'images' => $images,
                'images_count' => $images->count(),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error guardando evidencia de orden', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'message' => 'No fue posible guardar la evidencia.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**  Finalizar evidencia y generar PDF.   */
    public function finalize($order)
    {
        $order = Orders::findOrFail($order);

        $annex = OrderAnnex::with('images')
            ->where('order_id', $order->id)
            ->firstOrFail();

        // No permitir finalizar dos veces
        if ((int) $annex->status === 1) {
            return response()->json([
                'message' => 'Este anexo ya fue finalizado.',
            ], 422);
        }

        $images = $annex->images->sortBy('orden')->values();

        // Validar que existan fotografías
        if ($images->isEmpty()) {
            return response()->json([
                'message' => 'Debes agregar al menos una fotografía.',
            ], 422);
        }

        // Máximo 6 fotografías
        if ($images->count() > 6) {
            return response()->json([
                'message' => 'El anexo permite un máximo de 6 fotografías.',
            ], 422);
        }

        $pdfImages = [];

        foreach ($images as $image) {
            if (!Storage::disk('public')->exists($image->image)) {
                return response()->json([
                    'message' => 'No se encontró una de las fotografías del anexo.',
                    'image' => $image->image,
                ], 422);
            }

            $fullPath = Storage::disk('public')->path($image->image);
            $mime = mime_content_type($fullPath) ?: 'image/jpeg';

            $pdfImages[] = [
                'orden' => $image->orden,
                'src' => 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath)),
            ];
        }

        // Encabezado institucional
        $logoPath = public_path('imgPDF/logo.png');
        $logoImage = null;

        if (file_exists($logoPath)) {
            $logoMime = mime_content_type($logoPath) ?: 'image/png';

            $logoImage = 'data:' . $logoMime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Generar PDF: encabezado y cuadrícula de fotografías
        $pdf = Pdf::loadView('orders.order-annex-pdf', [
            'logoImage' => $logoImage,
            'images' => $pdfImages,
        ])->setPaper('a4', 'portrait');

        // Nombre basado en el número de orden
        $orderNumber = preg_replace(
            '/[^A-Za-z0-9_-]/', '_', $order->id
        );

        $pdfPath = 'anexos/anexo_orden_servicio_' . $orderNumber . '.pdf';

        // Guardar el PDF antes de eliminar las fotografías
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Marcar como finalizado y eliminar las imágenes temporales
        try {
            DB::transaction(function () use ($annex, $images) {
                $annex->status = 1;
                $annex->save();

                foreach ($images as $image) {
                    $image->delete();
                }
            });

            // Borrar archivos físicos después de finalizar
            foreach ($images as $image) {
                Storage::disk('public')->delete($image->image);
            }
        } catch (\Throwable $e) {
            // Si falla el guardado de estado, retirar el PDF generado
            Storage::disk('public')->delete($pdfPath);

            throw $e;
        }

        return response()->json([
            'message' => 'Anexo finalizado y PDF generado correctamente.',
            'pdf_url' => url('storage/' . $pdfPath),
            'pdf_path' => $pdfPath,
            'status' => 1,
        ]);
    }

    /**  Abrir PDF ya generado. */
    public function pdf($order)
    {
        $order = Orders::findOrFail($order);

        $annex = OrderAnnex::where('order_id', $order->id)->first();

        if (!$annex || (int) $annex->status !== 1) {
            return response()->json([
                'message' => 'La evidencia aún no ha sido finalizada.'
            ], 404);
        }

        $orderNumber = $order->no_orden ?? $order->id;

        $filename = 'anexo_orden_servicio_' . $orderNumber . '.pdf';
        $path = 'anexos/' . $filename;

        $disk = Storage::disk('public');

        if (!$disk->exists($path)) {
            return response()->json([
                'message' => 'El PDF de evidencia no fue encontrado.'
            ], 404);
        }

        return response()->file(
            $disk->path($path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]
        );
    }
}