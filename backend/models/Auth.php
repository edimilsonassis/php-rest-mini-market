<?php

namespace models;

final class Auth extends Model
{
    public ?Users $user = null;
    public ?string $jwt = null;
}