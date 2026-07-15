<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>ACR GENERAL</title>

<style>

    @page{
        margin:8px;
    }

    body{
        margin:0;
        font-family:Arial, Helvetica, sans-serif;
    }

</style>

</head>

<body>

    @include('F-10-05 REPORTE DE ACCIONES')

    <div style="page-break-after:always;"></div>

    @if($nonConformity->type == 'non_conformity')

        @include('TA-02-20 EVALUACION AC')

    @else

        @include('TA-02-20 EVALUACION AP')

    @endif

    @if(($nonConformity->evaluation->npr ?? 0) >= 100)

        <div style="page-break-after:always;"></div>

        @include('TA-02-23 ISHIKAWA')

        <div style="page-break-after:always;"></div>

        @include('F-02-24 RELACION')

        <div style="page-break-after:always;"></div>

        @include('F-02-25 PARETO')

    @endif

    <div style="page-break-after:always;"></div>

    @include('F-02-19 PLAN DE ACCION')

</body>

</html>