<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySettings extends Model
{
    protected $fillable = [
        'name',
        'business_line',
        'address',
        'rut',
        'email',
        'phone',
    ];

    /**
     * Obtiene la configuración actual de la empresa
     * Si no existe, crea una instancia por defecto
     */
    public static function updateCompanySettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'name' => 'VASTAGO',
                'business_line' => 'Restaurante',
                'address' => 'Temuco, Chile',
                'rut' => '00.000.000-0',
                'email' => 'contacto@vastago.cl',
                'phone' => '569999999',
            ]);
        }

        return $settings;
    }
}
