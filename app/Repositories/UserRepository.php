<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Http\Services\ConsultExternalApiService;
use App\Http\Services\NotificationUserApiService;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryContract;
use Cknow\Money\Money;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

final class UserRepository implements UserRepositoryContract
{
    protected $user;
    protected $consultExternalApiService;
    protected $notificationUserApiService;

    /**
     * UserRepository constructor.
     * @param User $user
     * @param ConsultExternalApiService $consultExternalApiService
     * @param NotificationUserApiService $notificationUserApiService
     */
    public function __construct(
        User $user,
        ConsultExternalApiService $consultExternalApiService,
        NotificationUserApiService $notificationUserApiService
    )
    {
        $this->user = $user;
        $this->consultExternalApiService = $consultExternalApiService;
        $this->notificationUserApiService = $notificationUserApiService;
    }

    /**
     * @param string $userId
     * @return object
     */
    public function show(string $userId): object
    {
        $user = $this->findUserById($userId);

        if ($user) {
            UserResource::withoutWrapping();

            return new UserResource($user);
        }

        return response()->json(['status' => 'error', 'message' => 'no data'], 404);
    }

    /**
     * @return UserCollection
     */
    public function all(): object
    {
        $users = $this->user->paginate(10);

        return new UserCollection($users);
    }

    /**
     * @param object $request
     * @return object
     */
    public function transfer(object $request): object
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'value' => 'required',
            'payer' => 'required',
            'payee' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => [
                    'value' => 'required',
                    'payer' => 'required',
                    'payee' => 'required',
                ]], 422);
        }

        if ($this->consultExternalApiService->authorize() !== 200) {
            return $this->transferNotAuthorized();
        }

        DB::beginTransaction();

        try {

            $this->subtractValueFromPayerWallet($data);
            $this->addValueToPayeeWallet($data);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Transfer OK'
        ], 201);
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    private function subtractValueFromPayerWallet(array $data): bool
    {
        $payer = $this->findUserById($data['payer']);

        if (!$this->checkIfUserExists($payer)) {
            throw new Exception('Payer does not exists');
        }

        if (!$this->checkIfPayerIsUser($payer->user_type)) {
            throw new Exception('Only individuals can transfer money');
        }

        $payerWallet = Money::parse($payer->user_wallet, 'BRL');
        $valueToTransfer = Money::parse($data['value'], 'BRL');

        if (!$this->checkPayerBalance($payerWallet, $valueToTransfer)) {
            throw new Exception('Payer balance is insufficient');
        }

        $payerSubtotal = $payerWallet->subtract($valueToTransfer);
        $payer->user_wallet = $payerSubtotal->getAmount();
        $this->notificationUserApiService->notify();

        return $payer->save();
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    private function addValueToPayeeWallet(array $data): bool
    {
        $valueToTransfer = Money::parse($data['value'], 'BRL');
        $payee = $this->findUserById($data['payee']);

        if (!$this->checkIfUserExists($payee)) {
            throw new Exception('Payee does not exists');
        }

        $payeeWallet = Money::parse($payee->user_wallet, 'BRL');
        $payeeSubtotal = $valueToTransfer->add($payeeWallet);
        $payee->user_wallet = $payeeSubtotal->getAmount();

        return $payee->save();
    }

    /**
     * @return JsonResponse
     */
    private function transferNotAuthorized(): JsonResponse
    {
        return response()->json([
            'message' => 'Transfer not authorized'
        ], 403);
    }

    /**
     * @param string $userId
     * @return object
     */
    private function findUserById(string $userId): ?object
    {
        return $this->user
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * @param object|null $user
     * @return bool
     */
    private function checkIfUserExists(?object $user): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * @param string $payer
     * @return bool
     */
    private function checkIfPayerIsUser(string $payer): bool
    {
        if ($payer !== 'user') {
            return false;
        }

        return true;
    }

    /**
     * @param object $payerWallet
     * @param object $valueToTransfer
     * @return bool
     */
    private function checkPayerBalance(object $payerWallet, object $valueToTransfer): bool
    {
        if ($payerWallet->greaterThanOrEqual($valueToTransfer)) {
            return true;
        }

        return false;
    }
}
