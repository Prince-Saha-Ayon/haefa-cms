@extends('layouts.app')


@section('content')
<div class="dt-content">
   <!-- Grid --
>
   <div class="row">
      <div class="col-xl-12 pb-3">
         <ol class="breadcrumb bg-white">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="active breadcrumb-item">Illness</li>
         </ol>
      </div>
      <!-- Grid Item -->
      <div class="col-xl-12">
         <!-- Entry Header -->
         <div class="dt-entry__header">
            <!-- Entry Heading -->
            <div class="dt-entry__heading">
               <h2 class="dt-page__title mb-0 text-primary"><i class="fas fa-th-list"></i> Illness</h2>
            </div>
            <!-- /entry heading -->
            <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New Illness','Save')">
            <i class="fas fa-plus-square"></i> Add New
            </button>
         </div>
         <!-- /entry header -->
         <!-- Card -->
         <div class="dt-card">
            <!-- Card Body -->
            <div class="dt-card__body">
               <form id="form-filter">
                  <div class="row">
                     <div class="form-group col-md-4">
                        <label for="name">Illness Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Illness name">
                     </div>
                     <div class="form-group col-md-8 pt-24">
                        <button type="button" class="btn btn-danger btn-sm float-right" id="btn-reset" data-toggle="tooltip" data-placement="top" data-original-title="Reset Data">
                        <i class="fas fa-redo-alt"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm float-right mr-2" id="btn-filter" data-toggle="tooltip" data-placement="top" data-original-title="Filter Data">
                        <i class="fas fa-search"></i>
                        </button>
                     </div>
                  </div>
               </form>
               <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                  <div class="row">
                     <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="dataTable_length">
                           <label>
                              Show 
                              <select name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm">
                                 <option value="5">5</option>
                                 <option value="10">10</option>
                                 <option value="15">15</option>
                                 <option value="25">25</option>
                                 <option value="50">50</option>
                                 <option value="100">100</option>
                                 <option value="1000">1,000</option>
                                 <option value="10000">10,000</option>
                                 <option value="-1">All</option>
                              </select>
                              entries
                           </label>
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-6">
                        <div class="float-right">
                           <div class="dt-buttons btn-group flex-wrap">
                              <div class="btn-group"><button class="btn btn-secondary buttons-collection dropdown-toggle buttons-colvis btn-sm text-white" tabindex="0" aria-controls="dataTable" type="button" aria-haspopup="true" aria-expanded="false"><span>Column</span></button></div>
                              <button class="btn btn-secondary buttons-print btn-sm text-white" tabindex="0" aria-controls="dataTable" type="button"><span>Print</span></button> <button class="btn btn-secondary buttons-csv buttons-html5 btn-sm text-white" tabindex="0" aria-controls="dataTable" type="button"><span>CSV</span></button> <button class="btn btn-secondary buttons-excel buttons-html5 btn-sm text-white" tabindex="0" aria-controls="dataTable" type="button"><span>Excel</span></button> <button class="btn btn-secondary buttons-pdf buttons-html5 btn-sm text-white" tabindex="0" aria-controls="dataTable" type="button"><span>PDF</span></button> <button class="btn btn-secondary btn-danger btn-sm delete_btn d-none text-white" tabindex="0" aria-controls="dataTable" type="button"><span>Delete</span></button> 
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-sm-12">
                        <table id="dataTable" class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" role="grid" aria-describedby="dataTable_info" style="width: 1000px;">
                           <thead class="bg-primary">
                              <tr role="row">
                                 <th class="text-center sorting_disabled" rowspan="1" colspan="1" style="width: 80px;" aria-label="
                                    ">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                       <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                 </th>
                                 <th class="text-center sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 53px;" aria-label="Sl: activate to sort column ascending">SL</th>
                                 <th class="text-center sorting_disabled" rowspan="1" colspan="1" style="width: 136px;" aria-label="Status">IllnessCode</th>
                                 <th class="text-center sorting_disabled" rowspan="1" colspan="1" style="width: 136px;" aria-label="Status">Description</th>
                                 <th class="text-center sorting_disabled" rowspan="1" colspan="1" style="width: 136px;" aria-label="Status">HOIllness</th>
                                 <th class="text-center sorting_disabled" rowspan="1" colspan="1" style="width: 136px;" aria-label="Status">FamilyHO</th>
                                 <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 177px;" aria-label="Action: activate to sort column ascending">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Cancer</td>
                                 <td class=" text-center">Cancer</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Malaria</td>
                                 <td class=" text-center">Malaria</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Skin disease</td>
                                 <td class=" text-center">Skin disease</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Asthma</td>
                                 <td class=" text-center">Asthma</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Hypertension</td>
                                 <td class=" text-center">Hypertension</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Diabetes</td>
                                 <td class=" text-center">Diabetes</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Surgery</td>
                                 <td class=" text-center">Surgery</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>TB</td>
                                 <td class=" text-center">TB</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Typhoid</td>
                                 <td class=" text-center">Typhoid</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Fracture/injury</td>
                                 <td class=" text-center">Fracture/injury</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Surgery</td>
                                 <td class=" text-center">Surgery</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Hepatitis</td>
                                 <td class=" text-center">Hepatitis</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Depression</td>
                                 <td class=" text-center">Depression</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Stroke</td>
                                 <td class=" text-center">Stroke</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="text-center dtr-control">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" value="3" class="custom-control-input select_data" onchange="select_single_item(3)" id="checkbox3">
                                       <label class="custom-control-label" for="checkbox3"></label>
                                    </div>
                                 </td>
                                 <td class=" text-center">1</td>
                                 <td>Dengue</td>
                                 <td class=" text-center">Dengue</td>
                                 <td class=" text-center">False</td>
                                 <td class=" text-center">True</td>
                                 <td>
                                    <div class="dropdown">
                                       <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-th-list text-white"></i>
                                       </button>
                                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item edit_data" data-id="3"><i class="fas fa-edit text-primary"></i> Edit</a> <a class="dropdown-item delete_data" data-id="3" data-name="API-SM421"><i class="fas fa-trash text-danger"></i> Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                           
                           </tbody>
                        </table>
                        <div id="dataTable_processing" class="dataTables_processing card" style="display: none;"><i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">Showing 1 to 2 of 2 entries</div>
                     </div>
                     <div class="col-sm-12 col-md-7">
                        <div class="float-right">
                           <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                              <ul class="pagination">
                                 <li class="paginate_button page-item previous disabled" id="dataTable_previous"><a href="#" aria-controls="dataTable" data-dt-idx="0" tabindex="0" class="page-link"><i class="fas fa-angle-left"></i></a></li>
                                 <li class="paginate_button page-item active"><a href="#" aria-controls="dataTable" data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
                                 <li class="paginate_button page-item next disabled" id="dataTable_next"><a href="#" aria-controls="dataTable" data-dt-idx="2" tabindex="0" class="page-link"><i class="fas fa-angle-right"></i></a></li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- /card body -->
         </div>
         <!-- /card -->
      </div>
      <!-- /grid item -->
   </div>
   <!-- /grid -->
</div>
@endsection
