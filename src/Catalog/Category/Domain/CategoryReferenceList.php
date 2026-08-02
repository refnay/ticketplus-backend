<?php

namespace App\Catalog\Category\Domain;

enum EventCategory: int
{
    case CONCERT = 0;
    case THEATER = 1;
    case SPORTS = 2;
    case CONFERENCE = 3;
    case WORKSHOP = 4;
    case COURSE = 5;
    case BUSINESS = 6;
    case TECHNOLOGY = 7;
    case GAMING = 8;
    case ANIME_COMICS = 9;
    case FAMILY = 10;
    case FOOD_DRINK = 11;
    case ART = 12;
    case CINEMA = 13;
    case FASHION = 14;
    case HEALTH_WELLNESS = 15;
    case RELIGIOUS = 16;
    case CHARITY = 17;
    case OUTDOOR = 18;
    case NIGHTLIFE = 19;
    case OTHER = 20;
}