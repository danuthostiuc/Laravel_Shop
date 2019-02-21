@component('mail::message')
#New Order<br>
{{ $order->name }}
{{ $order->email }}
{{ $order->comment }}
@component('mail::button', ['url' => url('/order') . '/'.  $order->id])
View order<br>
<table>
@foreach ($products as $row)
<tr>
<td class="cp_img">
<img src="{{ \Storage::url($row->image) }}"/>
</td>
<td class="cp_img">
<ul>
<li>{{ $row->title }}</li>
<li>{{ $row->description }}</li>
<li>{{ $row->price }}</li>
</ul>
</td>
</tr>
@endforeach
</table>
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
