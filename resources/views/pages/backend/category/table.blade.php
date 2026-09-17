@php
    use App\Enums\CategoryStatusEnum;
    $substring = $concat . '--';
@endphp
   <tr>
       <td class="text-center">
           {{ $item->id }}
       </td>
       <td class="fw-semibold fs-sm">
           <a>{{ $concat . $item->name }}</a>
       </td>
       <td class="fs-sm">{{ $item->slug }}</td>
       <td>
           @php
               $class = '';
               if ($item->status === CategoryStatusEnum::ACTIVE) {
                   $class = 'bg-info-light text-info';
               }
               if ($item->status === CategoryStatusEnum::INACTIVE) {
                   $class = 'bg-success-light text-success';
               }
               if ($item->status === CategoryStatusEnum::DELETED) {
                   $class = 'bg-danger-light text-danger';
               }
        @endphp
        <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill {{ $class }}">{{ $item->status }}</span>
    </td>
       <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d H:i:s') }}</td>
    <td class="text-center">
        <div class="btn-group">
            <a href="{{ route($adminPrefix . '.' . $categoryPrefix . '.edit', ['category' => $item->id ]) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" title="" data-bs-original-title="Edit">
                <i class="mdi mdi-pencil"></i>
            </a>
            @if($item->status !== CategoryStatusEnum::DELETED)
                <form name="ticketForm_{{ $item->id }}" action="{{ route($adminPrefix . '.' . $categoryPrefix . '.destroy', ['category' => $item->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-custom-delete" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete">
                        <i class="mdi mdi-delete"></i>
                    </a>
                </form>
            @endif
        </div>
    </td>
</tr>
@if(count($item['children']) > 0)
    @foreach($item['children'] as $itemChildren)
        @include('pages.backend.category.table', ['item' => $itemChildren, 'concat' => $substring])
    @endforeach
@endif
