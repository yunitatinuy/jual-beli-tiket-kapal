@extends('layout.main')

@section('container')
<main class ="my-8">
    <!-- carousel -->
<div class="container justify-center max-w-screen-xl w-4/5">
    <div id="myCarousel" class="carousel slide mb-14 bg-cover" data-bs-ride="carousel">
            <div class="carousel-indicators">
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>  
        <div class="carousel-inner h-[500px] rounded-3xl">
            <div class="carousel-item active">
                <img src="img/1.webp" alt="" class="w-full bg-cover">
                <div class="container">
                    <div class="carousel-caption text-start">
                        <h1>HAI</h1>
                        <p class="opacity-75">HELLO</p>
                        <p><a class="btn btn-lg btn-primary" href="#">Registrasi Cepat</a></p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/2.webp" alt="" class="w-full bg-cover">
                <div class="container">
                    <div class="carousel-caption">
                    <h1>HAI</h1>
                    <p>HELLO</p>
                    <p><a class="btn btn-lg btn-primary" href="#">HELOO</a></p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/3.jpg" alt="" class="w-full bg-cover">
                <div class="container">
                    <div class="carousel-caption text-end">
                    <h1>HAI</h1>
                    <p>HELLO</p>
                    <p><a class="btn btn-lg btn-primary" href="#">HELOO</a></p>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev rounded-3xl hover:bg-transparent" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next rounded-3xl hover:bg-transparent" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
    <!-- carousel end -->

  <!-- container -->
  <div class="container booking ">
    <div class="container justify-content-center p-3 w-4/5" style="border-radius:30px;">
      <div class="flex flex-row space-x-5 mx-4">
        <button onclick="tampilkanTampilan(1)" class="hover:bg-[#d97398] active:bg-[#d97398] focus:outline-none focus:ring focus:ring-[#d97398] hover:rounded-xl p-1 px-2">Sekali Pergi</button>
        <button onclick="tampilkanTampilan(2)" class="hover:bg-[#d97398] active:bg-[#d97398] focus:outline-none focus:ring focus:ring-[#d97398] hover:rounded-xl p-1 px-2">Pulang Pergi</button>
      </div>

        <!-- tampilan1 -->
        <div id="tampilan1" class="tampilan">
        <div class="flex flex-row my-2">
          <div class="col-lg-1 col-md-2 col-sm-2 ms-4">
            <label class="control-label" id="from-title-label-Ferry">Dari :</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">Jakarta</option>
              <option>Batam</option>
              <option>Medan</option>
              <option>Riau</option>
              </select>
            </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2"></div>
          <div class="col-lg-1 col-md-2 col-sm-2">
          <label class="control-label" id="from-title-label-Ferry">Ke :</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">Jakarta</option>
              <option>Batam</option>
              <option>Medan</option>
              <option>Riau</option>
              </select>
            </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2"></div>
          <div class="col-lg-1 col-md-2 col-sm-2">
          <label class="control-label" id="from-title-label-Ferry">Dewasa :</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
              <option>7</option>
              <option>8</option>
              <option>9</option>
              <option>10</option>
              </select>
            </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2"></div>
          <div class="col-lg-1 col-md-2 col-sm-2">
          <label class="control-label" id="from-title-label-Ferry">Anak-anak:</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
              <option>7</option>
              <option>8</option>
              <option>9</option>
              <option>10</option>
              </select>
            </div>
          </div>
        </div><br>

        <div class="flex flex-row ">
          <div class="col-lg-1 col-md-2 col-sm-2 ms-4">
            <label class="control-label" id="from-title-label-Ferry">Tanggal Berangkat :</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">1</option>
              <option>1</option>
              <option>2</option>
              <option>3</option>
              </select>
            </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2"></div>
          <div class="col-lg-1 col-md-2 col-sm-2">
          <label class="control-label" id="from-title-label-Ferry">Kelas :</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
              <option>7</option>
              <option>8</option>
              <option>9</option>
              <option>10</option>
              </select>
            </div>
          </div>
        </div>
        </div>
        <!-- tampilan1 end -->

        <!-- tampilan2 -->
        <div id="tampilan2" class="tampilan">
        <div class="flex flex-row">
          <div class="col-lg-1 col-md-2 col-sm-2 ms-4">
            <label class="control-label" id="from-title-label-Ferry">Dari :</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">Jakarta</option>
              <option>Batam</option>
              <option>Medan</option>
              <option>Riau</option>
              </select>
            </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2"></div>
          <div class="col-lg-1 col-md-2 col-sm-2">
          <label class="control-label" id="from-title-label-Ferry">Ke :</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">Jakarta</option>
              <option>Batam</option>
              <option>Medan</option>
              <option>Riau</option>
              </select>
            </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2"></div>
          <div class="col-lg-1 col-md-2 col-sm-2">
          <label class="control-label" id="from-title-label-Ferry">Dewasa :</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
              <option>7</option>
              <option>8</option>
              <option>9</option>
              <option>10</option>
              </select>
            </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2"></div>
          <div class="col-lg-1 col-md-2 col-sm-2">
          <label class="control-label" id="from-title-label-Ferry">Anak-anak:</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
              <option>7</option>
              <option>8</option>
              <option>9</option>
              <option>10</option>
              </select>
            </div>
          </div>
        </div><br>

        <div class="row" style=";background-color:; ">
          <div class="col-lg-1 col-md-2 col-sm-2 ms-4">
            <label class="control-label" id="from-title-label-Ferry">Tanggal Berangkat :</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">1</option>
              <option>1</option>
              <option>2</option>
              <option>3</option>
              </select>
            </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2"></div>
          <div class="col-lg-1 col-md-2 col-sm-2">
          <label class="control-label" id="from-title-label-Ferry">Tanggal Pulang :</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">1</option>
              <option>1</option>
              <option>2</option>
              <option>3</option>
              </select>
            </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2"></div>
          <div class="col-lg-1 col-md-2 col-sm-2">
          <label class="control-label" id="from-title-label-Ferry">Kelas :</label>
            <div id="divPax_Ferry" class="form-group">
              <select class="form-control valid" data-val="true" data-val-number="The field Pax must be a number." id="ddlPax_Ferry" name="Pax" style="border-radius: 5px 5px 5px 5px" fdprocessedid="yzn7p9"><option selected="selected">1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
              <option>7</option>
              <option>8</option>
              <option>9</option>
              <option>10</option>
              </select>
            </div>
          </div>
        </div>
        </div>
        <!-- tampilan2 end -->
    </div><br>
    <div class="flex justify-end">
      <a href="/login" class="font-sans inline-flex items-center px-3 py-2 text-sm font-medium text-center text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300 ">
        Cari Ferry
        <img src="/img/cari.png" alt="" class="w-4 ms-2">
      </a>
    </div>
  </div>

  <!-- /.container -->
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Ketika halaman dimuat, tampilkan tampilan default
  tampilkanTampilan(1);
});

function tampilkanTampilan(tampilan) {
  var tampilan1 = document.getElementById('tampilan1');
  var tampilan2 = document.getElementById('tampilan2');

  if (tampilan === 1) {
    tampilan1.classList.add('active');
    tampilan2.classList.remove('active');
  } else if (tampilan === 2) {
    tampilan1.classList.remove('active');
    tampilan2.classList.add('active');
  }
}
</script>
</body>
@endsection
