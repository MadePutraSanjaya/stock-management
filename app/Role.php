<?php

namespace App\Enums;

enum Role: string
{
   case ADMIN = 'ADMIN';
   case PEGAWAI = 'PEGAWAI';
   case PEGAWAI_KEUANGAN = 'PEGAWAI KEUANGAN';
}
