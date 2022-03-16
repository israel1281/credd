<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'terms',
        'email_verified_at',
        'status_id',
        'role_id',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'name',
        'bvn_verified',
        'phone_verified',
        'has_kyc',
        'has_pending_loan',
        'has_pending_loan_request',
        'is_admin', 'is_user',
        'can_update_balance',
        'has_kyc_pending',
        'has_kyc_rejected',
        'unread_notifications_count',
        'is_blocked', 'has_cards'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_blacklist_ng' => 'boolean'
    ];

    // Mutators
    public function getIsAdminAttribute() {
        return $this->attributes['role_id'] == role_admin();
    }

    public function getIsUserAttribute() {
        return $this->attributes['role_id'] == role_user();
    }

    public function getNameAttribute() {
        return $this->first_name.' '.$this->last_name;
    }

    public function getHasPendingLoanAttribute() {
        if (!isset($this->wallet)) return false;
        return
            (!!$this->wallet->loan_amt);
    }

    public function getHasPendingLoanRequestAttribute() {
        return
            (!!count($this->loanRequests()
            ->where('status_id', status_pending_id())
            ->get()));
    }

    public function getUnreadNotificationsCountAttribute() {
        return count($this->unreadNotifications);
    }

    public function getHasKycPendingAttribute() {
        return (!!count($this->kyc()->pending()->get()));
    }

    public function getHasKycRejectedAttribute() {
        return (!!count($this->kyc()->rejected()->get()));
    }

    public function getHasKycAttribute() {
        return isset($this->kyc);
    }

    public function getBvnVerifiedAttribute() {
        return isset($this->bvn);
    }

    public function getPhoneVerifiedAttribute() {
        return isset($this->bvn) && $this->phone_verified_at;
    }

    public function getIsBlockedAttribute() {
        return $this->attributes['status_id'] == status_blocked_id();
    }

    public function getHasCardsAttribute() {
        return $this->cards()->exists();
    }

    // Policies
    public function getCanUpdateBalanceAttribute() {
        return Gate::allows('update-balance');
    }

    // Scopes
    public function scopeAdmin($query) {
        return $query->where('role_id', role_admin());
    }

    // Relationships
    public function wallet() {
        return $this->hasOne(Wallet::class);
    }

    public function bvn() {
        return $this->hasOne(BvnVerification::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }

    public function kyc() {
        return $this->hasOne(Kyc::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function loanRequests() {
        return $this->hasMany(LoanRequest::class);
    }

    public function withdrawRequests() {
        return $this->hasMany(WithdrawRequest::class);
    }

    public function cards() {
        return $this->hasMany(Card::class);
    }

    public function autoDebitCharges() {
        return $this->hasMany(AutodebitCharges::class);
    }

    public function bpTransactions() {
        return $this->hasMany(BillPaymentTransactions::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }
}
