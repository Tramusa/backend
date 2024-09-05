<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Requisitions extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user', 'id_work_area', 'id_collaborator', 'date', 'id_parent_account', 'id_title_account', 'id_subtitle_account', 'id_mayor_account', 'observations', 'status', 'date_authorized', 'authorized', 'date_atended'
    ];

    public function product()
    {
        return $this->hasMany(DetailsRequisitions::class, 'id_product');
    }
    
    public function products()
    {
        return $this->hasMany(DetailsRequisitions::class, 'id_requisition');
    }

    public function work_areaInfo()
    {
        return $this->hasOne(WorkAreas::class, 'id', 'id_work_area');
    }

    public function collaboratorInfo()
    {
        return $this->hasOne(Collaborators::class, 'id', 'id_collaborator');
    }

    public function parent_accountInfo()
    {
        return $this->hasOne(ParentAccount::class, 'id', 'id_parent_account');
    }

    public function title_accountInfo()
    {
        return $this->hasOne(TitleAccount::class, 'id', 'id_title_account');
    }

    public function subtitle_accountInfo()
    {
        return $this->hasOne(SubTitleAccount::class, 'id', 'id_subtitle_account');
    }

    public function mayor_accountInfo()
    {
        return $this->hasOne(MayorAccount::class, 'id', 'id_mayor_account');
    }

    public function user_authorized()
    {
        return $this->hasOne(User::class, 'id', 'authorized');
    }
}
