<?php /**@var App\Persistence\Models\LeaveRequestHistory $item * */ ?>

<ul class="list-group">
    @foreach($history as $item)
        <li class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"> <i class="far fa-clock"></i> {{$item->created_at}}</h5>
            </div>
            <p class="mb-1">{{$item->event}}</p>
        </li>

    @endforeach
</ul>

