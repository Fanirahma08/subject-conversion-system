<?php

namespace App\Enums;

enum UserRole: string
{
    case Mahasiswa = 'mahasiswa';
    case PMB = 'pmb';
    case Kaprodi = 'kaprodi';
    case Dekan = 'dekan';
    case Rektor = 'rektor';
}
