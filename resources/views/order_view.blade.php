<html>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Menu</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $dataorderku)
            <tr>
                <td>{{$dataorderku['id']}}</td>
                <td>{{$dataorderku['menu']}}</td>
                <td>{{$dataorderku['harga']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</html>
{{-- <div>
    <!-- Walk as if you are kissing the Earth with your feet. - Thich Nhat Hanh -->
</div> --}}
