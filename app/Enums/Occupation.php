<?php

namespace App\Enums;

enum Occupation: string
{
    case FARMER = 'farmer';
    case FREELANCER = 'freelancer';
    case TEACHER = 'teacher';
    case DOCTOR = 'doctor';
    case BANKER = 'banker';
    case NGO_WORKER = 'ngo_worker';
    case DRIVER = 'driver';
    case BUSINESS = 'business';
    case EXPETRIATE = 'expetriate';
    case GOVT_SERVICE = 'govt_service';
    case PRIVATE_JOB = 'private_job';
    case SHOPKEEPER = 'shopkeeper';
    case CORPORATE_OFFICER = 'corporate_officer';
    case SELF_EMPLOYED = 'self_employed';
    case UNEMPLOYED = 'unemployed';
    case OTHER = 'other';
    case NONE = 'none';
}
