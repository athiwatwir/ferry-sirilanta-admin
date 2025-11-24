@extends('layouts.default')

@section('content')
<x-card>
    <div class="row mt-4">
        <div class="col-12 col-lg-12 mx-auto">
            <form novalidate="" class="bs-validate" id="fee-update-form" method="POST" action="https://app.tigerlineferry.com/fee-manage/update">
                <input type="hidden" name="_token" value="zoqO5unPJnDd4fGObZHQUGQfQDpIneIOSbXFghHC" autocomplete="off">

                <div class="card mb-4">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-12 d-block d-lg-none">
                                <h2 class="text-main-color-2 text-center">2C2P</h2>
                            </div>
                            <div class="col-12 col-md-10 order-2 order-lg-1">
                                <div class="row">
                                    <div class="col-12 d-none d-lg-block">
                                        <h2 class="text-main-color-2">2C2P</h2>
                                    </div>
                                    <div class="col-12 col-lg-6 border-lg-end border-2 border-bottom border-lg-bottom-0 border-color-main mb-3 mb-lg-0 pb-3 pb-lg-0">
                                        <h6 class="text-center mb-3 fw-bold">Processing fee (เลือกได้เพียงอย่างเดียว)</h6>
                                        <div class="checkgroup js-checkgroup" data-checkgroup-checkbox-single="true">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-check mb-2 float-end">
                                                                <input class="form-check-input form-check-input-primary is-pf-perperson-2C2P" type="checkbox" name="is_pf_perperson_2C2P" value="Y" id="perperson-pf-check-2C2P">
                                                                <label class="form-check-label" for="perperson-pf-check-2C2P">
                                                                    Per/person
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mb-2">
                                                            <div class="row">
                                                                <div class="col-5 d-flex align-items-center">
                                                                    <small>Regular</small>
                                                                </div>
                                                                <div class="col-7 ps-0 text-end">
                                                                    <div class="d-flex align-items-center justify-content-end">
                                                                        <input type="number" class="form-control form-control-sm px-2 w--60 text-center" value="0.00" id="regular-pf-2C2P" name="regular_pf_2C2P" step="any">
                                                                        <label for="regular-pf-2C2P" class="mb-0 ms-1 form-label smaller">Bath</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-6 border-start border-2">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input form-check-input-primary is-pf-perperson-2C2P" type="checkbox" name="is_pf_perperson_2C2P" value="N" id="allperson-pf-check-2C2P" checked="">
                                                        <label class="form-check-label" for="allperson-pf-check-2C2P">
                                                            % All person/ ticket
                                                        </label>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <input type="number" class="form-control form-control-sm text-center w-50" name="percent_pf_2C2P" value="5.57" step="any">
                                                        <label class="mb-0 ms-1 form-label smaller">%</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <h6 class="text-center mb-3 fw-bold">Service charge (เลือกได้เพียงอย่างเดียว)</h6>
                                        <div class="checkgroup js-checkgroup" data-checkgroup-checkbox-single="true">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-check mb-2 float-end">
                                                                <input class="form-check-input form-check-input-primary is-sc-perperson-2C2P" type="checkbox" name="is_sc_perperson_2C2P" value="Y" id="perperson-sc-check-2C2P" checked="">
                                                                <label class="form-check-label" for="perperson-sc-check-2C2P">
                                                                    Per/person
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mb-2">
                                                            <div class="row">
                                                                <div class="col-5 d-flex align-items-center">
                                                                    <small>Regular</small>
                                                                </div>
                                                                <div class="col-7 ps-0 text-end">
                                                                    <div class="d-flex align-items-center justify-content-end">
                                                                        <input type="number" class="form-control form-control-sm px-2 w--60 text-center" value="0.00" id="regular-sc-2C2P" name="regular_sc_2C2P" step="any">
                                                                        <label for="regular-sc-2C2P" class="mb-0 ms-1 form-label smaller">Bath</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-6 border-start border-2">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input form-check-input-primary is-sc-perperson-2C2P" type="checkbox" value="N" name="is_sc_perperson_2C2P" id="allperson-sc-check-2C2P">
                                                        <label class="form-check-label" for="allperson-sc-check-2C2P">
                                                            % All person/ ticket
                                                        </label>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <input type="number" class="form-control form-control-sm text-center w-50" name="percent_sc_2C2P" value="0.00" step="any">
                                                        <label class="mb-0 ms-1 form-label smaller">%</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 border-lg-start border-2 border-color-main order-1 order-lg-2 border-bottom border-lg-bottom-0 mb-3 mb-lg-0">
                                <h6 class="text-main-color-2 text-center text-lg-start mb-3 fw-bold">
                                    สูตรการใช้
                                </h6>
                                <div class="checkgroup js-checkgroup" data-checkgroup-checkbox-single="false">
                                    <div class="row">
                                        <div class="col-6 col-lg-12 border-sm-end border-2">
                                            <div class="form-check mb-3 text-center text-lg-start">
                                                <input class="form-check-input form-check-input-primary" type="checkbox" value="Y" id="isuse-pf-2C2P" name="isuse_pf_2C2P" checked="">
                                                <label class="form-check-label" for="isuse-pf-2C2P">
                                                    Processing fee
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-12 text-center text-lg-start">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input form-check-input-primary" type="checkbox" value="Y" id="isuse-sc-2C2P" name="isuse_sc_2C2P">
                                                <label class="form-check-label" for="isuse-sc-2C2P">
                                                    Service chage
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="is_fee[]" value="9c9d2cf1-de1f-459c-8291-3eb400da5d6b">
                    </div>
                </div>


                <div class="row btn-section">
                    <div class="col-12 text-end mt-4">
                        <button type="submit" data-button-id="68916c2970a18" class="btn button-green-bg border-radius-10 btn-sm me-3">
                            <span>Submit</span>
                            <i data-icon-after-click-id="68916c2970a1a" class="fi fi-loading-dots fi-spin d-none"></i>
                        </button>

                        <script type="text/javascript" defer="true">
                            document.addEventListener("DOMContentLoaded", function() {
                                const button = document.querySelector("[data-button-id='68916c2970a18']")
                                const icon_arter = document.querySelector("[data-icon-after-click-id='68916c2970a1a']")
                                const form_id = document.querySelector("#fee-update-form")
                                const fieldset_id = document.querySelector("#fee-update")

                                if (button instanceof HTMLElement) {
                                    button.addEventListener("click", function() {
                                        if (form_id.checkValidity()) {
                                            form_id.submit()
                                            icon_arter.classList.remove('d-none')
                                            fieldset_id.disabled = true
                                        }
                                    });
                                }
                            })

                        </script> <a href="https://app.tigerlineferry.com/fee-manage" class="btn btn-danger border-radius-10 btn-sm">Reset</a>
                        <small id="user-create-error-notice" class="text-danger mt-3"></small>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-card>

@stop
