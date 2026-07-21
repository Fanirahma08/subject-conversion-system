<?php

namespace App\Enums;

enum UserRole: string
{
    case Mahasiswa = 'mahasiswa';
    case PMB = 'pmb';
    case Kaprodi = 'kaprodi';
    case BAAK = 'baak';
    case Dekan = 'dekan';
    case WR1 = 'wr1';
    case Rektor = 'rektor';
}
