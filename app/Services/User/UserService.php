<?php

namespace App\Services\User;

use App\Events\ChangedEmail;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class UserService implements IUserService
{

    private function hashUserPasswordInput(array &$userData): void
    {
        $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);
    }

    public function store(array $recordInsertData): void
    {
        $this->hashUserPasswordInput($recordInsertData);
        $user = User::create($recordInsertData);

        Auth::login($user);
        event(new Registered($user));
    }

    private function buildUpdatePayload(array $inputRecordData): array
    {
        $dataOfUpdate = [];

        if (array_key_exists('name', $inputRecordData)) {
            $dataOfUpdate['name'] = $inputRecordData['name'];
        }

        if (array_key_exists('password', $inputRecordData)) {
            $this->hashUserPasswordInput($inputRecordData);
            $dataOfUpdate['password'] = $inputRecordData['password'];
        }

        if (array_key_exists('email', $inputRecordData)) {
            $dataOfUpdate['email'] = $inputRecordData['email'];
            $dataOfUpdate['email_verified_at'] = null;
        }

        return $dataOfUpdate;
    }

    private function resendEmailIfNecessary(array $updatePayload)
    {
        if (array_key_exists('email', $updatePayload)) {
            $user = Auth::user();
            event(new ChangedEmail($user));
        }
    }

    public function update(array $recordUpdateData): void
    {
        $dataOfUpdate = $this->buildUpdatePayload($recordUpdateData);
        $updateEffect = User::where('id', Auth::user()->id)->update($dataOfUpdate);

        if ($updateEffect === 0) {
            throw new Exception('Fail on update over user record.');
        }

        $this->resendEmailIfNecessary($dataOfUpdate);
    }

    public function destroy(): void
    {
        $id = Auth::user()->id;
        $deleteEffect = User::where('id', $id)->delete();

        if ($deleteEffect === 0) {
            throw new Exception('Fail on delete over user record.');
        }
    }
}
