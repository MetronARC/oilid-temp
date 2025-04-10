<?= $this->extend('template/index') ?>

<?= $this->section('page-content') ?>

<a href="/" class="logo" target="_blank">
    <img src="<?= base_url('img/oilid.png') ?>" alt="" style="width: 75px; height: 50px;" />
</a>

<div class="section">
    <div class="container">
        <div class="row full-height justify-content-center">
            <div class="col-12 text-center align-self-center py-5">
                <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    <div class="card-3d-wrap mx-auto">
                        <div class="card-3d-wrapper">
                            <div class="card-front">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <h4 class="mb-4 pb-3">What do you want to do?</h4>
                                        <div class="form-group mt-2">
                                            <a href="<?= base_url('/inspect') ?>" class="btn mt-4 w-100">
                                                <span class="d-flex align-items-center justify-content-center">
                                                    <i class="uil uil-search me-2" style="font-size: 24px;"></i>
                                                    Inspect
                                                </span>
                                            </a>
                                        </div>
                                        <div class="form-group mt-4">
                                            <a href="<?= base_url('/query') ?>" class="btn mt-4 w-100">
                                                <span class="d-flex align-items-center justify-content-center">
                                                    <i class="uil uil-database me-2" style="font-size: 24px;"></i>
                                                    Query
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>