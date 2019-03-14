<?php /**@var App\Persistence\Models\LeaveRequest $leaveRequest * */ ?>

<div class="blockquote">
    <h2>{{ $leaveRequest->employee->name }}
        <small class="text-muted"> - Szabadság igénye</small>
    </h2>

    <div class="blockquote-footer">Igényles leadva: {{ $leaveRequest->created_at }}</div>
</div>

<div class="container border p-2 mb-2">
    <div class="row mb-2">
        <div class="col col-md">
            Szabadság típusa:
        </div>
        <div class="col col-md-8">
            <span class="badge badge-secondary">{{ $leaveRequest->leaveType[0]->name}}</span>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col col-md">
            Szabadság szabály:
        </div>
        <div class="col col-md-8">
            {{$leaveRequest->leavePolicy->name}}
        </div>
    </div>

    <div class="row mb-2">
        <div class="col col-md">
            Időszak:
        </div>
        <div class="col col-md-8">
            {{ $leaveRequest->start_at }} - {{ $leaveRequest->end_at }}
        </div>
    </div>

    <div class="row mb-2">
        <div class="col col-md">
            Napok száma:
        </div>
        <div class="col col-md-8">
            <span class="badge badge-primary">{{ $leaveRequest->days }}</span>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col">
            Megjegyzés:
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <p>
                {{ $leaveRequest->comment }}
            </p>
        </div>
    </div>
</div>