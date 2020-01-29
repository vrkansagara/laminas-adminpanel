<?php

namespace Backend\Interfaces;

interface LoginInterface
{
    public function showLoginAction();

    public function processLoginAction();

    public function loginRedirectAction();

    public function logoutRedirectAction();
}
