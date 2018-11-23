<?php

namespace Rebing\Timber\Requests\Contexts;

use Auth;

class UserContext extends AbstractContext
{
    protected $userContext;

    public function getData(): array
    {
        if ($this->userContext) {
            return $this->userContext;
        }

        if (Auth::check()) {
            $user = Auth::user();
            $data = [
                'id' => (string)Auth::id(),
            ];

            if (isset($user->name)) {
                $data['name'] = $user->name;
            }
            if (isset($user->email)) {
                $data['email'] = $user->email;
            }

            return ['user' => $data];
        }

        return [];
    }

    public function setUserContext(array $data): void
    {
        $this->userContext = $data;
    }
}