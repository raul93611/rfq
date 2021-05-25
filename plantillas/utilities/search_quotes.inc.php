<?php
$quotes = [];
if(isset($_POST['buscar'])){
  echo $_POST['keyword'];
  Database::open_connection();
  $quotes = QuoteRepository::get_search_result(Database::get_connection(), $_POST['keyword'], $_SESSION['role'], $_SESSION['id_user']);
  Database::close_connection();
}
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Search</h1>
        </div>
        <div class="col-sm-6">

        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-search"></i> Quotes</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                  <form role="form" method="post" action="<?php echo SEARCH_QUOTES; ?>">
                    <div class="form-group">
                      <input type="search" name="keyword" class="form-control" placeholder="Search ..." required autofocus <?php if(isset($_POST['buscar'])){echo 'value="' . $_POST['keyword'] . '"';} ?>>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="buscar">Search</button>
                  </form>
                </div>
                <div class="col-md-4">

                </div>
              </div>
            </div>
          </div>
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-search"></i> Quotes</h3>
            </div>
            <div class="card-body">
              <?php
              QuoteRepository::print_search_results($quotes);
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
