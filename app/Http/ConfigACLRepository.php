<?php

namespace App\Http;

use Alexusmai\LaravelFileManager\Services\ACLService\ACLRepository;

class ConfigACLRepository implements ACLRepository
{
    /**
     * Get user ID
     *
     * @return mixed
     */
    public function getUserID()
    {
        return \Auth::id();
    }

    /**
     * Get ACL rules list for user
     *
     * @return array
     */
    public function getRules(): array
    {
        // if (\Auth::user()->isAdmin()) {
        //     return [
        //         ['disk' => 'documents', 'path' => '*', 'access' => 2],
        //     ];
        // }

        return [
            ['disk' => 'public', 'path' => '/', 'access' => 2],
            ['disk' => 'public', 'path' => 'files*', 'access' => 2],
        ];
    }
}