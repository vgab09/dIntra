<?php /** @var \App\Http\Components\ToolbarLink\ToolbarLinkableInterface[] $items */ ?>
<ul class="nav nav-pills">
    @foreach($items as $item)
        <li class="nav-item btn-toolbar">
            {!! $item->render() !!}
        </li>
    @endforeach
</ul>
