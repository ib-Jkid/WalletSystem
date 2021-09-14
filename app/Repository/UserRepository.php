<?php
namespace App\Repository;

use App\Models\User;
use App\Repository\Eloquent\BaseRepository;


class UserRepository extends BaseRepository implements IUserRepository
{
   public function __construct(User $model)
   {
       parent::__construct($model);

   }


}