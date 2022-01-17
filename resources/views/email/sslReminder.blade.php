<p>Sites that need ssl cert updates</p>

<table>
    <tr>
        <th>Site</th>
        <th>Cert Expire Date</th>
    </tr>
    @foreach ($list as $item)
    <tr>
        <td><a href="{{$item->site}}">{{$item->site}}</a></td>
        <td>{{$item->expire_date->format('m/d/Y')}}</td>
    </tr>
    @endforeach
</table>
