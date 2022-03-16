<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\BvnVerification
 *
 * @property int $id
 * @property int $user_id
 * @property string $bvn
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $middlename
 * @property string|null $gender
 * @property string|null $phone
 * @property string|null $birthdate
 * @property int $valid_lastname
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification query()
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification whereBvn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification whereMiddlename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BvnVerification whereValidLastname($value)
 */
	class BvnVerification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Deposit
 *
 * @property int $id
 * @property string $payment_method
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit newQuery()
 * @method static \Illuminate\Database\Query\Builder|Deposit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Deposit withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Deposit withoutTrashed()
 */
	class Deposit extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Kyc
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $dob
 * @property string $gender
 * @property string $phone
 * @property string $address
 * @property string $kin_name
 * @property string $kin_phone
 * @property string $relationship_status
 * @property string $city
 * @property string $state
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Status $status
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc newQuery()
 * @method static \Illuminate\Database\Query\Builder|Kyc onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc pending()
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc rejected()
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereKinName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereKinPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereRelationshipStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kyc whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Kyc withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Kyc withoutTrashed()
 */
	class Kyc extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LoanRequest
 *
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property int $interest
 * @property \Illuminate\Support\Carbon|null $start_at
 * @property \Illuminate\Support\Carbon|null $expire_at
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $amount_string
 * @property-read mixed $interest_string
 * @property-read \App\Models\Status $status
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest active()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest newQuery()
 * @method static \Illuminate\Database\Query\Builder|LoanRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRequest whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|LoanRequest withTrashed()
 * @method static \Illuminate\Database\Query\Builder|LoanRequest withoutTrashed()
 */
	class LoanRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Status
 *
 * @property int $id
 * @property string $title
 * @property string $colour
 * @property string|null $description
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_active
 * @property-read mixed $is_cancelled
 * @method static \Illuminate\Database\Eloquent\Builder|Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status query()
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereColour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereUpdatedAt($value)
 */
	class Status extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $txn_no
 * @property int $amt
 * @property int $prev_bal
 * @property int $new_bal
 * @property string $model_type
 * @property int $model_id
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $amount
 * @property-read mixed $amount_string
 * @property-read \App\Models\Status $status
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Query\Builder|Transaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction pending()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereNewBal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePrevBal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTxnNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Transaction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Transaction withoutTrashed()
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property int $status_id
 * @property int $role_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string $phone
 * @property int $terms
 * @property bool|null $is_blacklist_ng
 * @property string|null $avatar_url
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BvnVerification|null $bvn
 * @property-read mixed $bvn_verified
 * @property-read mixed $has_kyc
 * @property-read mixed $has_kyc_pending
 * @property-read mixed $has_kyc_rejected
 * @property-read mixed $has_pending_loan
 * @property-read mixed $has_pending_loan_request
 * @property-read mixed $is_admin
 * @property-read mixed $is_user
 * @property-read mixed $name
 * @property-read mixed $unread_notifications_count
 * @property-read \App\Models\Kyc|null $kyc
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LoanRequest[] $loanRequests
 * @property-read int|null $loan_requests_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Role $role
 * @property-read \App\Models\Status $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\Wallet|null $wallet
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WithdrawRequest[] $withdrawRequests
 * @property-read int|null $withdraw_requests_count
 * @method static \Illuminate\Database\Eloquent\Builder|User admin()
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsBlacklistNg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * App\Models\Wallet
 *
 * @property int $id
 * @property int $user_id
 * @property int $status_id
 * @property string $acc_no
 * @property int $amt
 * @property int $loan_amt
 * @property string $type
 * @property int $is_default
 * @property int|null $max_amt_transfer
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $amount
 * @property-read mixed $amount_string
 * @property-read mixed $loan_amount
 * @property-read mixed $loan_amount_string
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet default()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereAccNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereAmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereLoanAmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereMaxAmtTransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUserId($value)
 */
	class Wallet extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\WithdrawRequest
 *
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property string $account_name
 * @property string $bank_name
 * @property string $account_number
 * @property string|null $reason
 * @property int $status_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $amount_string
 * @property-read \App\Models\Status $status
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawRequest whereUserId($value)
 */
	class WithdrawRequest extends \Eloquent {}
}

