<html>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Grup</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $datalbumku)
            <tr>
                <td>{{$datalbumku['id']}}</td>
                <td>{{$datalbumku['nama']}}</td>
                <td>{{$datalbumku['grup']}}</td>
                <td>{{$datalbumku['harga']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</html>
{{-- <div>
    <!-- Walk as if you are kissing the Earth with your feet. - Thich Nhat Hanh -->
</div> --}}
