<?php

namespace App\Models;

class BranchStatus {
    public const DRAFT = 'draft';
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const CORRECTION = 'correction';
    public const REJECTED = 'rejected';
    public const DE_REGISTERED = 'deregistered';
    public const TEMP_CLOSED = 'temp_closed';

}