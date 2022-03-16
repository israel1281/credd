<?php

use App\Models\Status;

function status_active_id() {
    return Status::where('title', 'active')->first()->id;
}

function status_inactive_id() {
    return Status::where('title', 'inactive')->first()->id;
}

function status_blocked_id() {
    return Status::where('title', 'blocked')->first()->id;
}

function status_completed_id() {
    return Status::where('title', 'completed')->first()->id;
}

function status_cancelled_id() {
    return Status::where('title', 'cancelled')->first()->id;
}

function status_pending_id() {
    return Status::where('title', 'pending')->first()->id;
}

function status_failed_id() {
    return Status::where('title', 'failed')->first()->id;
}

function status_accepted_id() {
    return Status::where('title', 'accepted')->first()->id;
}

function status_rejected_id() {
    return Status::where('title', 'rejected')->first()->id;
}

function status_processing_id() {
    return Status::where('title', 'processing')->first()->id;
}
