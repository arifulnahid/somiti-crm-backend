<?php

namespace App\Enums;

enum Relationship: string
{
    case MOTHER = 'mother';
    case FATHER = 'father';
    case BROTHER = 'brother';
    case SISTER = 'sister';
    case HUSBAND = 'husband';
    case WIFE = 'wife';
    case SON = 'son';
    case DAUGHTER = 'daughter';
    case MOTHER_IN_LAW = 'mother_in_law';
    case FATHER_IN_LAW = 'father_in_law';
}
