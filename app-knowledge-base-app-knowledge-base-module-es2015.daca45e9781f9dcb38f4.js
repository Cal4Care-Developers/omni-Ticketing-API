(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["app-knowledge-base-app-knowledge-base-module"],{

/***/ "./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/create-category/create-category.component.html":
/*!*************************************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/create-category/create-category.component.html ***!
  \*************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (" \n\t<div >\n\t\t<div class=\"main-wrapper main-wrapper-1\">\n\t\t\t<div class=\"navbar-bg\"></div>\n\t\t\t \n\t\t\t\n\t\t\t<!-- Main Content -->\n\t\t\t<div class=\"section-body\">\n\t\t\t\t<div class=\"card mt-2 mb-0\">\n\t\t\t\t\t<div class=\"col-12 col-lg-12\">\n\t\t\t\t\t<div class=\"card-body\" >\n\t\t\t\t\t  <div class=\"row\">\n\t\t\t\t\t\t<div class=\"col-md-12\">\n\t\t\t\t\t\t  <div class=\"dropdown select-option header-select-dropdown mr-3\">\n\t\t\t\t\t\t\t<div class=\"select-option-label\" data-toggle=\"dropdown\"\n\t\t\t\t\t\t\t\tclass=\"dropdown filter-btn info badge badge-secondary\">\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\n\t\t\t\t\n\t\t\t\t\t\t\t\n\t\t\t\t\t\t</div>\n\t\t\t\t\t\t  <a routerLink=\"/kb/create-category\" class=\"badge badge-success mr-2\">\n\t\t\t\t\t\t\t<span class=\"icon\">\n\t\t\t\t\t\t\t\t<i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i>\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t\t&nbsp; Category\n\t\t\t\t\t\t  </a>\n\t\t\t\t\t\t  <!-- <a routerLink=\"/kb/videoupload\" class=\"badge badge-warning mr-2\">\n\t\t\t\t\t\t\t<span class=\"icon\">\n\t\t\t\t\t\t\t  \n\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t\t&nbsp; Video Link\n\t\t\t\t\t\t  </a> -->\n\t\t\t\t\n\t\t\t\t\t\t  <a routerLink=\"/kb/displaypage\" class=\"badge badge-primary mr-2\">\n\t\t\t\t\t\t\t<span class=\"icon\">\n\t\t\t\t\t\t\t\t<i class=\"fas fa-list\" aria-hidden=\"true\"></i>\n\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t\t&nbsp; List\n\t\t\t\t\t\t  </a>\n\t\t\t\t\n\t\t\t\t\t\t  <a routerLink=\"/kb/upload\" class=\"badge badge-info mr-2\">\n\t\t\t\t\t\t\t<span class=\"icon\">\n\t\t\t\t\t\t\t\t<i class=\"fa fa-upload\" aria-hidden=\"true\"></i>\n\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t\t&nbsp; Post Article\n\t\t\t\t\t\t  </a>\n\t\t\t\t\t\t  <a  data-toggle=\"collapse\" href=\"#generalFilter\"  class=\"badge badge-secondary mr-2 fr\">\n\t\t\t\t\t\t\t<span class=\"icon\">\n\t\t\t\t\t\t\t\t<i class=\"fa fa-plus\" aria-hidden=\"true\"></i>\n\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t\t&nbsp; Create Category\n\t\t\t\t\t\t  </a>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t  </div>\n\t\t\t\t\t  </div>\n\t\t\t\t\t  </div>\n\t\t\t\t\n\t\t\t\t\t\n\t\t\t\t<section class=\"section\">\n\t\t\t\t\t<ul class=\"breadcrumb breadcrumb-style \" >\n\t\t\t\t\t\t<li class=\"breadcrumb-item\">\n\t\t\t\t\t\t\t<h4 class=\"page-title m-b-0\">Category Management</h4>\n\t\t\t\t\t\t</li>\n\t\t\t\t\t\t<!-- <div class=\"col-12 col-lg-12\" >\n\t\t\t\t\t\t  <a  class=\"badge badge-info mr-2 fr\">\n\t\t\t\t\t\t\t<span class=\"icon\">\n\t\t\t\t\t\t\t\t<i class=\"fa fa-plus\" aria-hidden=\"true\"></i>\n\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t\t&nbsp; Create Category\n\t\t\t\t\t\t  </a>\n\t\t\t\t\t\t</div> -->\n\t\t\t\t\t</ul>\n\t\t\t\t\t<div id=\"accordion\" class=\"card accordion\">\n\t\t\t\t\t\t<div id=\"generalFilter\" class=\"card-body collapse\" data-parent=\"#accordion\">\n\t\t\t\t\t\t  <div id=\"wizard_horizontal\" class=\"wizard filterTab\">\n\t\t\t\t\t\t\t\t<div class=\"section-body\">\n\t\t\t\t\t\t\t\t\t<div class=\"row\">\n\t\t\t\t\t\t\t\t\t\t<div style=\"margin: auto\" class=\"col-12 col-lg-6\">\n\t\t\t\t\t\t\t\t\t\t\t<form [formGroup]=\"addcategory\">\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"card\">\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card-header\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<h4>Create New Category</h4>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card-body\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"row\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Enter Category Name*</label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-list-alt\"></i>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"text\"  class=\"form-control\" formControlName=\"category_name\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Enter Description*</label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-list-alt\"></i>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<textarea type=\"text\"  class=\"form-control\" formControlName=\"description\" ></textarea>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-lg-12 mt-2 mb-3\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"submit-btn-group fr\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<button class=\"btn btn-secondary mr-2\"  data-toggle=\"collapse\" href=\"#generalFilter\" (click)=\"cancel1()\">Cancel</button>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<button type=\"submit\" value=\"add\" class=\"btn btn-info\" [disabled]=\"!addcategory.valid\" (click)=\"postD2(addcategory)\">Add Category</button>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t</form>\n\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-lg-6\">\n\t\t\t\t\t\t\t\t\t\t\t<form [formGroup]=\"addsubcategory\">\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"card\">\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card-header\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<h4>Create New Sub Category</h4>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card-body\">\n\t\t\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-18\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Select Category*</label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<select [(ngModel)]=\"catselect\" [ngModelOptions]=\"{standalone: true}\" class=\"form-control\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<option *ngFor=\"let categorylist of categorylists\" [ngValue]=\"categorylist.id\">{{categorylist.category_name}}</option>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</select>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Enter Sub Category Name* </label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-stream\"></i>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"text\" id=\"sub-category-name\" class=\"form-control\" formControlName=\"subcategory_name\"\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tid=\"sub-category-name\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-lg-12 mt-2 mb-3\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"submit-btn-group fr\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<button class=\"btn btn-secondary mr-2\" (click)=\"cancel()\">Cancel</button>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<button type=\"submit\" value=\"submit\" (click)=\"postA2(addsubcategory)\" class=\"btn btn-info\">Add Sub Category</button>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t</form>\n\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t     </div>\n\t\t\t\t\t   </div>\n\t\t\t\t   </div>\n\t\t\t\t   <div class=\"col-12\">\n\t\t\t\t\t<div class=\"card card-primary\">\n\t\t\t\t\t\t<div class=\"card-header\">\n\t\t\t\t\t\t\t<h4>KnowledgeBase Category</h4>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t\t<div class=\"collapse show\" id=\"referralCollapse\">\n\t\t\t\t\t\t\t<div class=\"card-body\">\n\t\t\t\t\t\t\t\t<div class=\"table-responsive\">\n\t\t\t\t\t\t\t\t\t<table class=\"table table-striped dataTable\">\n\t\t\t\t\t\t\t\t\t\t<thead>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<th>S. No</th>\t\t\t\t\t\t\t\t\t\t\t\t \n\t\t\t\t\t\t\t\t\t\t\t\t<th>Category Name</th>\t\t\t\t\t\t\t\t\t\t\t\t \n\t\t\t\t\t\t\t\t\t\t\t\t<th>Sub Category</th>\t\t\t\t\t\t\t\t\t\t\t\t \n\t\t\t\t\t\t\t\t\t\t\t\t<th>Status</th>\n\t\t\t\t\t\t\t\t\t\t\t\t<th>Action</th>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t</thead>\n\t\t\t\t\t\t\t\t\t\t<tbody>\n\t\t\t\t\t\t\t\t\t\t\t<tr *ngFor=\"let category of new_categorylists; let i=index\">\n\t\t\t\t\t\t\t\t\t\t\t\t<td>{{i+1}}</td>\n \t\t\t\t\t\t\t\t\t\t\t\t<td>{{category.category_name}}</td> \n\t\t\t\t\t\t\t\t\t\t\t\t <td>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<ul class=\"badge-list\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<li *ngFor=\"let item of category.subcategory\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span class=\"badge subcategory-badge\">\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div>{{item}}</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i  class=\"far fa-edit\" title=\"Update\" (click)=\"UpdateSubcat(item)\"></i>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"far fa-trash-alt\" title=\"Delete\" (click)=\"RemoveSubcat(item)\"></i>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</li>\n\t\t\t\t\t\t\t\t\t\t\t\t\t</ul>\n\t\t\t\t\t\t\t\t\t\t\t\t</td>\n\t\t\t\t\t\t\t\t\t\t\t\t <td>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div style=\"cursor: pointer;\" (click)=\"ToggleStatus(category)\" [class]=\"category.status == 1 ? 'badge badge-success' : 'badge badge-warning'\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<span *ngIf=\"category.status=='1'\">ON</span><span *ngIf=\"category.status=='0'\">OFF</span>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t \n\t\t\t\t\t\t\t\t\t\t\t\t</td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"action-btn-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<span class=\"user-icon\" style=\"cursor: pointer;\"><i class=\"far fa-edit\" (click)=\"edit_category(category.id)\"></i></span>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<span class=\"user-icon\" style=\"cursor: pointer;\"><i class=\"far fa-trash-alt\" (click)=\"deleteCategory(category.id)\"></i></span>\n\t\t\t\t\t\t\t\t\t\t\t\t</td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr *ngIf=\"recordNotFound == true\">\n\t\t\t\t\t\t\t\t\t\t\t\t<td colspan=\"12\">Data not found</td>\n\t\t\t\t\t\t\t\t\t\t\t </tr>\n\t\t\t\t\t\t\t\t\t\t</tbody>    \n\t\t\t\t\t\t\t\t\t</table>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t\t</section>\n\t\t \n\t\t\t \n\t\t</div>\n\t\t\n\t\t</div>\n\n\t</div>\n\t\n\t<div class=\"modal fade bd-example-modal-md\" id=\"edit_category\">\n\t\t<div class=\"modal-dialog modal-md\">\n\t\t\t<div class=\"modal-content\">\n\t\t\t\t\n\t\t\t\t\t<div class=\"modal-header\">\n\t\t\t\t\t\t<h5 class=\"modal-title\" >Update Category</h5>\n\t\t\t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n\t\t\t\t\t\t\t<span aria-hidden=\"true\">&times;</span>\n\t\t\t\t\t\t</button>\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"modal-body\">\n\t\n\t\t\t\t\t\t<div class=\"row\">\n\t\t\t\t\t\t\t<div class=\"col-md-12\">\n\t\t\t\t\t\t\t\t<div class=\"form-group\">\n\t\t\t\t\t\t\t\t\t<label for=\"department_name\">Category Name</label>\n\t\t\t\t\t\t\t\t\t<input type=\"text\" id=\"edit_category_name\" class=\"form-control\">\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n\t\n\t\t\t\t\t\t\t<div class=\"col-md-12\">\n\t\t\t\t\t\t\t\t<div class=\"form-group\">\n\t\t\t\t\t\t\t\t\t<label for=\"department_name\">Category Description</label>\n\t\t\t\t\t\t\t\t\t<textarea type=\"text\" id=\"edit_category_description\" class=\"form-control\"  ></textarea>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<!-- <mat-form-field class=\"form-controls\" appearance=\"none\">\n\t\t\t\t\t\t\t\t<mat-chip-list    #editsubcategory>\n\t\t\t\t\t\t\t\t  <mat-chip *ngFor=\"let items of editsubcateogry\" [selectable]=\"selectable\"\n\t\t\t\t\t\t\t\t\t\t   [removable]=\"removable\" (removed)=\"editremove(items)\">\n\t\t\t\t\t\t\t\t\t{{items.name}}\n\t\t\t\t\t\t\t\t\t<mat-icon matChipRemove  >cancel</mat-icon>\n\t\t\t\t\t\t\t\t  </mat-chip>\n\t\t\t\t\t\t\t\t  <input  placeholder=\"\"\n\t\t\t\t\t\t\t\t\t\t [matChipInputFor]=\"editsubcategory\"\n\t\t\t\t\t\t\t\t\t\t [matChipInputSeparatorKeyCodes]=\"separatorKeysCodes\"\n\t\t\t\t\t\t\t\t\t\t [matChipInputAddOnBlur]=\"addOnBlur\"\n\t\t\t\t\t\t\t\t\t\t (matChipInputTokenEnd)=\"editadd($event)\">\n\t\t\t\t\t\t\t\t</mat-chip-list>\n\t\t\t\t\t\t\t  </mat-form-field> -->\n\t\t\t\t\t\t</div>\n\t\t\t\t\t\t<div class=\"col-12 col-lg-12 mt-2 mb-3\">\n\t\t\t\t\t\t\t<div class=\"submit-btn-group fr\">\n\t\t\t\t\t\t\t\t<button class=\"btn btn-secondary mr-2\" data-dismiss=\"modal\" data-toggle=\"collapse\" >Cancel</button>\n\t\t\t\t\t\t\t\t<button type=\"submit\" value=\"add\" class=\"btn btn-info\" (click)=\"Updatecategory(addcategory)\">Update</button>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div> \n\t\t\t</div>\n\t\t</div>\n\t</div>");

/***/ }),

/***/ "./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/displaypage/displaypage.component.html":
/*!*****************************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/displaypage/displaypage.component.html ***!
  \*****************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<!-- <!DOCTYPE html>\r\n<html lang=\"en\"> -->\r\n\r\n<!-- <head>\r\n\t<meta charset=\"UTF-8\">\r\n\t<meta content=\"width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no\" name=\"viewport\">\r\n\t<title>Helpdesk</title>\r\n\t<link rel=\"shortcut icon\" href=\"assets/assets1/img/custom-images/favicon.ico\" type=\"image/x-icon\">\r\n\t<link rel=\"icon\" href=\"assets/assets1/img/custom-images/favicon.ico\" type=\"image/x-icon\">\r\n</head> -->\r\n\r\n<!-- <body> -->\r\n\t<!-- <div class=\"loader\"></div> -->\r\n\t<div id=\"app\">\r\n\t\t<div class=\"main-wrapper main-wrapper-1\">\r\n\t\t\t<div class=\"navbar-bg\"></div>\r\n\t\t\t<!-- <nav class=\"navbar navbar-expand-lg main-navbar sticky\">\r\n\t\t\t\t<div class=\"form-inline mr-auto\">\r\n\t\t\t\t\t<ul class=\"navbar-nav mr-3\">\r\n\t\t\t\t\t\t<li><a href=\"#\" data-toggle=\"sidebar\" class=\"nav-link nav-link-lg\r\ncollapse-btn\"> <i data-feather=\"menu\"></i></a></li>\r\n\t\t\t\t\t\t<li><a href=\"#\" class=\"nav-link nav-link-lg fullscreen-btn\">\r\n\t\t\t\t\t\t\t\t<i data-feather=\"maximize\"></i>\r\n\t\t\t\t\t\t\t</a></li>\r\n\t\t\t\t\t</ul>\r\n\t\t\t\t</div>\r\n\t\t\t\t<ul class=\"navbar-nav navbar-right header-btn-group btn-group\">\r\n\t\t\t\t\t<li>\r\n\t\t\t\t\t\t<a href=\"#\" class=\"btn btn-success\"><i class=\"fas fa-plus\"></i> Add New Category</a>\r\n\t\t\t\t\t</li>\r\n\t\t\t\t\t<li>\r\n\t\t\t\t\t\t<a href=\"#\" class=\"btn btn-danger btn-icon icon-left\">\r\n\t\t\t\t\t\t\t<i class=\"fas fa-sign-out-alt\"></i> Logout\r\n\t\t\t\t\t\t</a>\r\n\t\t\t\t\t</li>\r\n\r\n\t\t\t\t</ul>\r\n\t\t\t</nav> -->\r\n\t\t\t<!-- Main Content -->\r\n\t\t\t<div class=\"section-body\">\r\n\t\t\t\t<div class=\"card mt-2 mb-0\">\r\n\t\t\t\t<div class=\"col-12 col-lg-12\">\r\n\t\t\t\t\t<div class=\"card-body\" >\r\n\t\t\t\t\t  <div class=\"row\">\r\n\t\t\t\t\t\t<div class=\"col-md-12\">\r\n\t\t\t\t\t\t  <div class=\"dropdown select-option header-select-dropdown mr-3\">\r\n\t\t\t\t\t\t\t<div class=\"select-option-label\" data-toggle=\"dropdown\"\r\n\t\t\t\t\t\t\t\tclass=\"dropdown filter-btn info badge badge-secondary\">\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\r\n\t\t\t\t\r\n\t\t\t\t\t\t\t\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t  <a routerLink=\"/kb/create-category\" class=\"badge badge-success mr-2\">\r\n\t\t\t\t\t\t\t<span class=\"icon\">\r\n\t\t\t\t\t\t\t\t<i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i>\r\n\t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t&nbsp; Category\r\n\t\t\t\t\t\t  </a>\r\n\t\t\t\t\t\t  <!-- <a routerLink=\"/kb/videoupload\" class=\"badge badge-warning mr-2\">\r\n\t\t\t\t\t\t\t<span class=\"icon\">\r\n\t\t\t\t\t\t\t \r\n\t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t&nbsp; Video Link\r\n\t\t\t\t\t\t  </a> -->\r\n\t\t\t\t\r\n\t\t\t\t\t\t  <a routerLink=\"/kb/displaypage\" class=\"badge badge-primary mr-2 pointer-event-none\">\r\n\t\t\t\t\t\t\t<span class=\"icon\">\r\n\t\t\t\t\t\t\t\t<i class=\"fas fa-list\" aria-hidden=\"true\"></i>\r\n\t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t&nbsp; List\r\n\t\t\t\t\t\t  </a>\r\n\t\t\t\t\r\n\t\t\t\t\t\t  <a routerLink=\"/kb/upload\" class=\"badge badge-info mr-2\">\r\n\t\t\t\t\t\t\t<span class=\"icon\">\r\n\t\t\t\t\t\t\t\t<i class=\"fa fa-upload\" aria-hidden=\"true\"></i>\r\n\t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t&nbsp;  Post Article\r\n\t\t\t\t\t\t  </a>\r\n\t\t\t\t\t\t  <a target=\"_blank\" href=\"https://ticketing.mconnectapps.com/kb/\" class=\"badge badge-danger mr-2 fr\">\r\n\t\t\t\t\t\t\t<span class=\"icon\">\r\n\t\t\t\t\t\t\t\t<i class=\"fa fa-globe\"></i>\r\n\t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t&nbsp; Visit WebSite\r\n\t\t\t\t\t\t  </a>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t  </div>\r\n\t\t\t\t\t  </div>\r\n\t\t\t\t\t  </div>\r\n\t\t\t\t\t<div class=\"section-body\">\r\n\t\t\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t\t\t<div class=\"col-12\">\r\n\t\t\t\t\t\t\t\t<div class=\"card card-primary\">\r\n\t\t\t\t\t\t\t\t\t<div class=\"card-header\">\r\n\t\t\t\t\t\t\t\t\t\t<h4>KnowledgeBase Management</h4>\r\n\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t<div class=\"collapse show\" id=\"referralCollapse\">\r\n\t\t\t\t\t\t\t\t\t\t<div class=\"card-body\">\r\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"table-responsive\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t<!-- <table class=\"table table-striped dataTable\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<thead>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<tr>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>S. No</th>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>Title</th>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>Category Name</th>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>TYPE</th>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>Featured</th>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>Action</th>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</tr>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</thead>\r\n                                                    <tbody> -->\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<!-- <tr *ngFor=\"let category of category_list; let i=index\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td>{{i+1}}</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td>{{category.post_title}}</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td>{{category.category_name}}</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span *ngIf=\"category.display_type=='1'\">Private</span>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span *ngIf=\"category.display_type=='2'\">Public</span>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span *ngIf=\"category.display_type=='3'\">Featured</span>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div style=\"cursor: pointer;\" [class]=\"category.featured == 1 ? 'badge badge-success' : 'badge badge-warning'\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span *ngIf=\"category.featured=='1'\">Yes</span><span *ngIf=\"category.status=='0'\">No</span>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"action-btn-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span class=\"user-icon\" style=\"cursor: pointer;\"><i class=\"far fa-edit\" (click)=\"editpost(category.id)\"></i></span>\r\n                                    \t\t\t\t\t\t\t<span class=\"user-icon\" style=\"cursor: pointer;\"><i class=\"far fa-trash-alt\" (click)=\"deletepost(category.id)\"></i></span>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</tr> -->\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t  <div *ngFor=\"let category of category_list; let i=index\"> \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div  id=\"accordion\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"accordion\" >\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div (click)=\"ShowSinglecategory('panel-body_' + category.id,category.id)\" class=\"accordion-header\" role=\"button\" data-toggle=\"collapse\" [attr.data-target]=\"'#panel-body_' + category.id\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<h4>{{category.category_name}}<span class=\"fr\"><i class=\"fas fa-plus\"></i></span>\t</h4>\t\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"accordion-body help-manual-groups collapse\"  id=\"panel-body_{{category.id}}\" data-parent=\"#accordion\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t  <div class=\"accord-btn-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<table class=\"table table-striped dataTable\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<thead>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<tr>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>S. No</th>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>Title</th>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>Category Name</th>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>TYPE</th>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>Featured</th>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>Action</th>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</tr>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</thead>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<tbody> \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<tr *ngFor=\"let item of category_items; let i=index\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td>{{i+1}}</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td>{{item.post_title}}</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td>{{item.category_name}}</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span *ngIf=\"item.display_type=='1'\">Private</span>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span *ngIf=\"item.display_type=='2'\">Public</span>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div  [class]=\"item.featured == '1' ? 'badge badge-success' : 'badge badge-warning'\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span *ngIf=\"item.featured=='1'\">Yes</span><span *ngIf=\"item.featured=='0'\">No</span>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"action-btn-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span class=\"user-icon\" style=\"cursor: pointer;\"><i class=\"far fa-edit\" (click)=\"editpost(item.id)\"></i></span>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span class=\"user-icon\" style=\"cursor: pointer;\"><i class=\"far fa-trash-alt\" (click)=\"deletepost(item.id)\"></i></span>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</tr>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<tr *ngIf=\"catItemsnotFound\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tNo data found\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</tr>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</tbody>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</table>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t  </div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t  </div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<tr *ngIf=\"recordNotFound == true\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td colspan=\"12\">Data not found</td>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t </tr>\r\n                                                    <!-- </tbody>     -->\r\n\t\t\t\t\t\t\t\t\t\t\t\t<!-- </table> -->\r\n\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t</div>\r\n\r\n\t\t\t</div>\r\n\t\t\t<footer class=\"main-footer\">\r\n\t\t\t\t<div class=\"footer-center text-center pb-2 pt-2\">\r\n\t\t\t\t\tCopyright &copy; 2021 <a href=\"https://cal4care.com/\">Cal4care</a>\r\n\t\t\t\t\t<div class=\"bullet\"></div> All Rights Reservered\r\n\t\t\t\t</div>\r\n\t\t\t</footer>\r\n\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n<!-- </body>\r\n\r\n</html> -->\r\n\r\n\r\n\r\n\r\n");

/***/ }),

/***/ "./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/edit-page/edit-page.component.html":
/*!*************************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/edit-page/edit-page.component.html ***!
  \*************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<!-- <!DOCTYPE html>\r\n<html lang=\"en\"> -->\r\n\r\n<!-- <head>\r\n\t<meta charset=\"UTF-8\">\r\n\t<meta content=\"width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no\" name=\"viewport\">\r\n\t<title>Helpdesk</title>\r\n\t<link rel=\"shortcut icon\" href=\"assets/assets1/img/custom-images/favicon.ico\" type=\"image/x-icon\">\r\n\t<link rel=\"icon\" href=\"assets/assets1/img/custom-images/favicon.ico\" type=\"image/x-icon\">\r\n</head> -->\r\n\r\n<!-- <body> -->\r\n\t<!-- <div class=\"loader\"></div> -->\r\n\t<div id=\"app\">\r\n\t\t<div class=\"main-wrapper main-wrapper-1\">\r\n\t\t\t<div class=\"navbar-bg\"></div>\r\n\t\t\t<!-- <nav class=\"navbar navbar-expand-lg main-navbar sticky\">\r\n\t\t\t\t<div class=\"form-inline mr-auto\">\r\n\t\t\t\t\t<ul class=\"navbar-nav mr-3\">\r\n\t\t\t\t\t\t<li><a href=\"#\" data-toggle=\"sidebar\" class=\"nav-link nav-link-lg\r\ncollapse-btn\"> <i data-feather=\"menu\"></i></a></li>\r\n\t\t\t\t\t\t<li><a href=\"#\" class=\"nav-link nav-link-lg fullscreen-btn\">\r\n\t\t\t\t\t\t\t\t<i data-feather=\"maximize\"></i>\r\n\t\t\t\t\t\t\t</a></li>\r\n\t\t\t\t\t</ul>\r\n\t\t\t\t</div>\r\n\t\t\t\t<ul class=\"navbar-nav navbar-right header-btn-group btn-group\">\r\n\t\t\t\t\t<li>\r\n\t\t\t\t\t\t<a href=\"#\" class=\"btn btn-success\"><i class=\"fas fa-plus\"></i> Add New Category</a>\r\n\t\t\t\t\t</li>\r\n\t\t\t\t\t<li>\r\n\t\t\t\t\t\t<a href=\"#\" class=\"btn btn-danger btn-icon icon-left\">\r\n\t\t\t\t\t\t\t<i class=\"fas fa-sign-out-alt\"></i> Logout\r\n\t\t\t\t\t\t</a>\r\n\t\t\t\t\t</li>\r\n\r\n\t\t\t\t</ul>\r\n\t\t\t</nav> -->\r\n\t\t\t\r\n\t\t\t<!-- Main Content -->\r\n\t\t\t<form [formGroup] = \"editform\">\r\n\t\t\t<div class=\"section-body\">\r\n\t\t\t\t<div class=\"card mt-2 mb-0\">\r\n\t\t\t\t<div class=\"col-12 col-lg-8\">\r\n\t\t\t\t\t<div class=\"card-body\" >\r\n\t\t\t\t\t  <div class=\"row\">\r\n\t\t\t\t\t\t<div class=\"col-md-9\">\r\n\t\t\t\t\t\t  <div class=\"dropdown select-option header-select-dropdown mr-3\">\r\n\t\t\t\t\t\t\t<div class=\"select-option-label\" data-toggle=\"dropdown\"\r\n\t\t\t\t\t\t\t\tclass=\"dropdown filter-btn info badge badge-secondary\">\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\r\n\t\t\t\t\r\n\t\t\t\t\t\t\t\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t  <a routerLink=\"/kb/create-category\" class=\"badge badge-success mr-2\">\r\n\t\t\t\t\t\t\t<span class=\"icon\">\r\n\t\t\t\t\t\t\t\t<i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i>\t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t&nbsp; Category\r\n\t\t\t\t\t\t  </a>\r\n\t\t\t\t\t\t  <!-- <a routerLink=\"/kb/videoupload\" class=\"badge badge-warning mr-2\">\r\n\t\t\t\t\t\t\t<span class=\"icon\">\r\n \t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t&nbsp; Video Link\r\n\t\t\t\t\t\t  </a> -->\r\n\t\t\t\t\r\n\t\t\t\t\t\t  <a routerLink=\"/kb/displaypage\" class=\"badge badge-primary mr-2\">\r\n\t\t\t\t\t\t\t<span class=\"icon\">\r\n\t\t\t\t\t\t\t\t<i class=\"fas fa-list\" aria-hidden=\"true\"></i>\r\n\r\n\t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t&nbsp; List\r\n\t\t\t\t\t\t  </a>\r\n\t\t\t\t\r\n\t\t\t\t\t\t  <a routerLink=\"/kb/upload\" class=\"badge badge-info mr-2\">\r\n\t\t\t\t\t\t\t<span class=\"icon\">\r\n\t\t\t\t\t\t\t\t<i class=\"fa fa-upload\" aria-hidden=\"true\"></i>\r\n\t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t&nbsp; Post Article\r\n\t\t\t\t\t\t  </a>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t  </div>\r\n\t\t\t\t\t  </div>\r\n\t\t\t\t\t  </div>\r\n\t\t\t\t<section class=\"section\">\r\n\t\t\t\t\t<ul class=\"breadcrumb breadcrumb-style \">\r\n\t\t\t\t\t\t<li class=\"breadcrumb-item\">\r\n\t\t\t\t\t\t\t<h4 class=\"page-title m-b-0\">Update Article</h4>\r\n\t\t\t\t\t\t</li>\r\n\t\t\t\t\t</ul>\r\n\t\t\t\t\t<div class=\"section-body\">\r\n\t\t\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t\t\t<div class=\"col-12 col-lg-12\">\r\n\t\t\t\t\t\t\t\t<div class=\"card\">\r\n\t\t\t\t\t\t\t\t\t<div class=\"card-header\">\r\n\t\t\t\t\t\t\t\t\t\t<h4>Content</h4>\r\n\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t<div class=\"card-body\">\r\n\t\t\t\t\t\t\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"company-name\">Post Title*</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-file-signature\"></i>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"text\" id=\"edit_post_title\" class=\"form-control\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-3\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"company-name\">Post Show / Hide*</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-switch mt-2\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"checkbox\" checked=\"{{post_status == 1 ? 'checked' : ''}}\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tname=\"custom-switch-checkbox\" \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tclass=\"custom-switch-input\" id=\"edit_status\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span class=\"custom-switch-indicator\"></span>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"checkbox\" class=\"custom-control-input\" id=\"edit_featured\" mdbInput>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-control-label\" for=\"edit_featured\">Featured</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-3\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"radio\" class=\"custom-control-input\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tid=\"private\" value=\"1\"   formControlName=\"displaytype\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t  mdbInput>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-control-label\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tfor=\"private\">private</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"radio\" class=\"custom-control-input\" id=\"public\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tvalue=\"2\" formControlName=\"displaytype\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t  mdbInput>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-control-label\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tfor=\"public\">public</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\t\t\t\r\n\t\t\t\t\t\t\t\t\t\t\t</div>\t\t\t\r\n\t\t\t\t\t\t\t\t\t\t\t  <!-- <div class=\"col-12 col-md-6 col-lg-3\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"radio\" class=\"custom-control-input\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tid=\"private\" value=\"1\" \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tname=\"defaultExampleRadios\" mdbInput>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-control-label\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tfor=\"private\">private</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t\t \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"radio\" class=\"custom-control-input\" id=\"public\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tvalue=\"2\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tname=\"defaultExampleRadios\" mdbInput>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-control-label\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tfor=\"public\">public</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"radio\" class=\"custom-control-input\" id=\"Both\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tvalue=\"3\" \r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tname=\"defaultExampleRadios\" mdbInput>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-control-label\" for=\"Both\">Both</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t</div>   -->\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t\r\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Posted by*</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-user\"></i>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<select class=\"form-control\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<option id=\"opt\" value=\"admin\">Admin</option>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</select>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\r\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Category*</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-list-alt\"></i>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<!-- <select (change)=\"getsubcategory()\" [(ngModel)]=\"catselect\" [ngModelOptions]=\"{standalone: true}\" class=\"form-control\" formControlName=\"catselect\"> -->\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<select (change)=\"getsubcategory()\" class=\"form-control\" formControlName=\"catselect\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<option *ngFor=\"let categorylist of categorylists\" [ngValue]=\"categorylist.id\">{{categorylist.category_name}}</option>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</select>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t<!-- <div class=\"col-12 col-md-6 col-lg-6\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Select Sub Category*</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-stream\"></i>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<select   class=\"form-control\" formControlName=\"subcatselect\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<option *ngFor=\"let subcategorylist of subcategorylists\" value=\"{{subcategorylist.id}}\">{{subcategorylist.sub_category_name}}</option>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</select>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t</div> -->\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\r\n\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-sm-12 col-12\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card card-purple\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card-header\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<h4>Post Content</h4>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"collapse support-ticket-panel show\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card-body p-0\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<textarea type=\"text\" id=\"edit_ck_editor\"></textarea>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-lg-12 mt-2 mb-3\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"submit-btn-group fr\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<button  class=\"btn btn-secondary mr-2\" (click)=\"goback()\">Cancel</button>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<button type=\"submit\" value=\"submit\" (click)=\"update(editform)\" class=\"btn btn-success\">Update Changes</button>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t</div>\r\n\t\t\t\t</section>\r\n\t\t\t</div>\r\n\t\t\t</div>\r\n\t\t\t</form>\r\n\t\t\t<footer class=\"main-footer\">\r\n\t\t\t\t<div class=\"footer-center text-center pb-2 pt-2\">\r\n\t\t\t\t\tCopyright &copy; 2021 <a href=\"https://cal4care.com/\">Cal4care</a>\r\n\t\t\t\t\t<div class=\"bullet\"></div> All Rights Reservered\r\n\t\t\t\t</div>\r\n\t\t\t</footer>\r\n\t\t</div>\r\n\r\n\t</div>\r\n\t<script src=\"https://cdn.ckeditor.com/ckeditor5/29.1.0/classic/ckeditor.js\"></script>\r\n\t<script>\r\n\t\tClassicEditor\r\n\t\t\t.create(document.querySelector('#ck-editor'))\r\n\t\t\t.catch(error => {\r\n\t\t\t\tconsole.error(error);\r\n\t\t\t});\r\n\t</script>\r\n<!-- </body>\r\n\r\n</html> -->");

/***/ }),

/***/ "./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/uploadpage/uploadpage.component.html":
/*!***************************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/uploadpage/uploadpage.component.html ***!
  \***************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<!-- <div class=\"loader\"></div> -->\r\n<div id=\"app\">\r\n\t<div class=\"main-wrapper main-wrapper-1\">\r\n\t\t<div class=\"navbar-bg\"></div>\r\n\t\t<!-- Main Content -->\r\n\t\t<form [formGroup]=\"uploadform\">\r\n\t\t\t<div class=\"section-body\">\r\n\t\t\t\t<div class=\"card mt-2 mb-0\">\r\n\t\t\t\t\t<div class=\"col-12 col-lg-8\">\r\n\t\t\t\t\t\t<div class=\"card-body\">\r\n\t\t\t\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t\t\t\t<div class=\"col-md-9\">\r\n\t\t\t\t\t\t\t\t\t<div class=\"dropdown select-option header-select-dropdown mr-3\">\r\n\t\t\t\t\t\t\t\t\t\t<div class=\"select-option-label\" data-toggle=\"dropdown\"\r\n\t\t\t\t\t\t\t\t\t\t\tclass=\"dropdown filter-btn info badge badge-secondary\">\r\n\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\r\n\r\n\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t<a routerLink=\"/kb/create-category\" class=\"badge badge-success mr-2\">\r\n\t\t\t\t\t\t\t\t\t\t<span class=\"icon\">\r\n\t\t\t\t\t\t\t\t\t\t\t<i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i>\t\r\n\t\t\t\t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t\t\t\t&nbsp; Category\r\n\t\t\t\t\t\t\t\t\t</a>\r\n\t\t\t\t\t\t\t\t\t<!-- <a routerLink=\"/kb/videoupload\" class=\"badge badge-warning mr-2\">\r\n\t\t\t\t\t\t\t\t\t\t<span class=\"icon\">\r\n \t\t\t\t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t\t\t\t&nbsp; Video Link\r\n\t\t\t\t\t\t\t\t\t</a> -->\r\n\r\n\t\t\t\t\t\t\t\t\t<a routerLink=\"/kb/displaypage\" class=\"badge badge-primary mr-2\">\r\n\t\t\t\t\t\t\t\t\t\t<span class=\"icon\">\r\n\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-list\" aria-hidden=\"true\"></i>\r\n\t\t\t\t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t\t\t\t&nbsp; List\r\n\t\t\t\t\t\t\t\t\t</a>\r\n\r\n\t\t\t\t\t\t\t\t\t<a routerLink=\"/kb/upload\" class=\"badge badge-info mr-2\">\r\n\t\t\t\t\t\t\t\t\t\t<span class=\"icon\">\r\n\t\t\t\t\t\t\t\t\t\t\t<i class=\"fa fa-upload\" aria-hidden=\"true\"></i>\t\t\t\t\t\t\t\t\t\t</span>\r\n\t\t\t\t\t\t\t\t\t\t&nbsp;  Post Article\r\n\t\t\t\t\t\t\t\t\t</a>\r\n\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<section class=\"section\">\r\n\t\t\t\t\t\t<ul class=\"breadcrumb breadcrumb-style \">\r\n\t\t\t\t\t\t\t<li class=\"breadcrumb-item\">\r\n\t\t\t\t\t\t\t\t<h4 class=\"page-title m-b-0\">POST Article</h4>\r\n\t\t\t\t\t\t\t</li>\r\n\t\t\t\t\t\t</ul>\r\n\t\t\t\t\t\t<div class=\"section-body\">\r\n\t\t\t\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t\t\t\t<div class=\"col-12 col-lg-12\">\r\n\t\t\t\t\t\t\t\t\t<div class=\"card\">\r\n\t\t\t\t\t\t\t\t\t\t<div class=\"card-header\">\r\n\t\t\t\t\t\t\t\t\t\t\t<h4>Content</h4>\r\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"card-header-action\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t<a (click)=\"addcat()\" class=\"btn btn-success\"><i\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\tclass=\"fas fa-plus\"></i> Add New Category</a>\r\n\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t<div class=\"card-body\">\r\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"company-name\">Post Title*</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-file-signature\"></i>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"text\" id=\"post_title\" class=\"form-control\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-3\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"company-name\">Post Show / Hide*</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-switch mt-2\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"checkbox\" id=\"status_check\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tname=\"custom-switch-checkbox\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tclass=\"custom-switch-input\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span class=\"custom-switch-indicator\"></span>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"checkbox\" class=\"custom-control-input\" id=\"featured\" mdbInput>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-control-label\" for=\"featured\">Featured</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-3\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<!-- Default unchecked -->\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"radio\" class=\"custom-control-input\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tid=\"private\" value=\"1\" formControlName=\"private\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tname=\"defaultExampleRadios\" mdbInput>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-control-label\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tfor=\"private\">private</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<!-- Default checked -->\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"radio\" class=\"custom-control-input\" id=\"public\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tvalue=\"2\" formControlName=\"private\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tname=\"defaultExampleRadios\" mdbInput>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-control-label\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tfor=\"public\">public</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Category*</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-list-alt\"></i>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<!-- (change)=\"getsubcategory()\" -->\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<select (change)=\"getsubcategory()\" [(ngModel)]=\"catselect\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tid=\"catselect\" [ngModelOptions]=\"{standalone: true}\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tclass=\"form-control\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<option *ngFor=\"let categorylist of categorylists\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t[ngValue]=\"categorylist.id\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t{{categorylist.category_name}}</option>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</select>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Select Sub Category*</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-stream\"></i>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<select [(ngModel)]=\"subcatselect\" id=\"subcatselect\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t[ngModelOptions]=\"{standalone: true}\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tclass=\"form-control\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<option *ngFor=\"let subcategorylist of subcategorylists\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tvalue=\"{{subcategorylist.id}}\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t{{subcategorylist.sub_category_name}}</option>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</select>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Posted by*</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-user\"></i>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<select class=\"form-control\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<option id=\"opt\" value=\"Admin\">Admin</option>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</select>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Upload Documents*</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"file\" id=\"file\" name=\"file\" class=\"form-control\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\trequired=\"required\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t<!-- <div class=\"col-12 col-md-6 col-lg-12\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Quote Text</label>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<textarea type=\"text\" id=\"quote_content\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tclass=\"form-control\"></textarea>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div> -->\r\n\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-sm-12 col-12\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card card-purple\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card-header\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<h4>Post Content</h4>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"collapse support-ticket-panel show\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card-body p-0\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<form class=\"composeForm\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<textarea type=\"text\" id=\"ck_editor\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tclass=\"form-control\"></textarea>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</form>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-lg-12 mt-2 mb-3\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"submit-btn-group fr\">\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<button class=\"btn btn-secondary mr-2\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t(click)=\"cancel()\">Cancel</button>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<button type=\"submit\" value=\"submit\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t(click)=\"postupload(uploadform)\"\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tclass=\"btn btn-success\">Update Changes</button>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t</section>\r\n\t\t\t\t</div>\r\n\t\t\t</div>\r\n\t\t</form>\r\n\t\t<footer class=\"main-footer\">\r\n\t\t\t<div class=\"footer-center text-center pb-2 pt-2\">\r\n\t\t\t\tCopyright &copy; 2021 <a href=\"https://cal4care.com/\">Cal4care</a>\r\n\t\t\t\t<div class=\"bullet\"></div> All Rights Reservered\r\n\t\t\t</div>\r\n\t\t</footer>\r\n\t</div>\r\n\r\n</div>\r\n\r\n\r\n\r\n<!-- </body>\r\n\r\n</html> -->");

/***/ }),

/***/ "./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/video-upload/video-upload.component.html":
/*!*******************************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/video-upload/video-upload.component.html ***!
  \*******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<!-- <!DOCTYPE html>\n<html lang=\"en\"> -->\n\n<!-- <head>\n\t<meta charset=\"UTF-8\">\n\t<meta content=\"width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no\" name=\"viewport\">\n\t<title>Helpdesk</title>\n\t<link rel=\"shortcut icon\" href=\"assets/assets1/img/custom-images/favicon.ico\" type=\"image/x-icon\">\n\t<link rel=\"icon\" href=\"assets/assets1/img/custom-images/favicon.ico\" type=\"image/x-icon\">\n</head> -->\n\n<!-- <body> -->\n\t<!-- <div class=\"loader\"></div> -->\n\t<div id=\"app\">\n\t\t<div class=\"main-wrapper main-wrapper-1\">\n\t\t\t<div class=\"navbar-bg\"></div>\n\t\t\t<!-- <nav class=\"navbar navbar-expand-lg main-navbar sticky\">\n\t\t\t\t<div class=\"form-inline mr-auto\">\n\t\t\t\t\t<ul class=\"navbar-nav mr-3\">\n\t\t\t\t\t\t<li><a href=\"#\" data-toggle=\"sidebar\" class=\"nav-link nav-link-lg\ncollapse-btn\"> <i data-feather=\"menu\"></i></a></li>\n\t\t\t\t\t\t<li><a href=\"#\" class=\"nav-link nav-link-lg fullscreen-btn\">\n\t\t\t\t\t\t\t\t<i data-feather=\"maximize\"></i>\n\t\t\t\t\t\t\t</a></li>\n\t\t\t\t\t</ul>\n\t\t\t\t</div>\n\t\t\t\t<ul class=\"navbar-nav navbar-right header-btn-group btn-group\">\n\t\t\t\t\t<li>\n\t\t\t\t\t\t<a href=\"#\" class=\"btn btn-success\"><i class=\"fas fa-plus\"></i> Add New Category</a>\n\t\t\t\t\t</li>\n\t\t\t\t\t<li>\n\t\t\t\t\t\t<a href=\"#\" class=\"btn btn-danger btn-icon icon-left\">\n\t\t\t\t\t\t\t<i class=\"fas fa-sign-out-alt\"></i> Logout\n\t\t\t\t\t\t</a>\n\t\t\t\t\t</li>\n\n\t\t\t\t</ul>\n\t\t\t</nav> -->\n\t\t\t\n\t\t\t<!-- Main Content -->\n\t\t\t<form [formGroup] = \"videoform\">\n\t\t\t<div class=\"section-body\">\n\t\t\t\t<div class=\"card mt-2 mb-0\">\n\t\t\t\t<div class=\"col-12 col-lg-12\">\n\t\t\t\t\t<div class=\"card-body\" >\n\t\t\t\t\t  <div class=\"row\">\n\t\t\t\t\t\t<div class=\"col-md-9\">\n\t\t\t\t\t\t  <div class=\"dropdown select-option header-select-dropdown mr-3\">\n\t\t\t\t\t\t\t<div class=\"select-option-label\" data-toggle=\"dropdown\"\n\t\t\t\t\t\t\t\tclass=\"dropdown filter-btn info badge badge-secondary\">\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\n\t\t\t\t\n\t\t\t\t\t\t\t\n\t\t\t\t\t\t</div>\n\t\t\t\t\t\t  <a routerLink=\"/kb/create-category\" class=\"badge badge-success mr-2\">\n\t\t\t\t\t\t\t<span class=\"icon\">\n\t\t\t\t\t\t\t  <!-- <i class=\"fas fa-ticket-alt\"></i> -->\n\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t\t&nbsp; Category\n\t\t\t\t\t\t  </a>\n\t\t\t\t\t\t  <a routerLink=\"/kb/videoupload\" class=\"badge badge-warning mr-2\">\n\t\t\t\t\t\t\t<span class=\"icon\">\n\t\t\t\t\t\t\t  <!-- <i class=\"fas fa-ticket-alt\"></i> -->\n\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t\t&nbsp; Video Link\n\t\t\t\t\t\t  </a>\n\t\t\t\t\n\t\t\t\t\t\t  <a routerLink=\"/kb/displaypage\" class=\"badge badge-primary mr-2\">\n\t\t\t\t\t\t\t<span class=\"icon\">\n\t\t\t\t\t\t\t  <!-- <i class=\"fas fa-palette\"></i> -->\n\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t\t&nbsp; List\n\t\t\t\t\t\t  </a>\n\t\t\t\t\n\t\t\t\t\t\t  <a routerLink=\"/kb/upload\" class=\"badge badge-info mr-2\">\n\t\t\t\t\t\t\t<span class=\"icon\">\n\t\t\t\t\t\t\t  <!-- <i class=\"fas fa-cog\"></i> -->\n\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t\t&nbsp; Document Upload\n\t\t\t\t\t\t  </a>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t  </div>\n\t\t\t\t\t  </div>\n\t\t\t\t\t  </div>\n\t\t\t\t<section class=\"section\">\n\t\t\t\t\t<ul class=\"breadcrumb breadcrumb-style \">\n\t\t\t\t\t\t<li class=\"breadcrumb-item\">\n\t\t\t\t\t\t\t<h4 class=\"page-title m-b-0\">Video Upload</h4>\n\t\t\t\t\t\t</li>\n\t\t\t\t\t</ul>\n\t\t\t\t\t<div class=\"section-body\">\n\t\t\t\t\t\t<div class=\"row\">\n\t\t\t\t\t\t\t<div class=\"col-12 col-lg-12\">\n\t\t\t\t\t\t\t\t<div class=\"card\">\n\t\t\t\t\t\t\t\t\t<div class=\"card-header\">\n\t\t\t\t\t\t\t\t\t\t<h4>Video</h4>\n\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t<div class=\"card-body\">\n\t\t\t\t\t\t\t\t\t\t<div class=\"row\">\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"company-name\">Post Title*</label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-file-signature\"></i>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"text\" id=\"post_title\" formControlName=\"post_title\" class=\"form-control\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-3\">\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"company-name\">Post Show / Hide*</label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-switch mt-2\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"checkbox\" id=\"status\" formControlName=\"status\" checked=\"{{status == 1 ? 'checked' : ''}}\"\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tname=\"custom-switch-checkbox\" \n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tclass=\"custom-switch-input\" id=\"status\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span class=\"custom-switch-indicator\"></span>\n\t\t\t\t\t\t\t\t\t\t\t\t\t</label>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-3\">\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t<!-- Default unchecked -->\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"radio\" class=\"custom-control-input\" id=\"private\" value=\"1\" formControlName=\"private\" name=\"defaultExampleRadios\"mdbInput>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-control-label\" for=\"private\">private</label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t\t\t<!-- Default checked -->\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"radio\" class=\"custom-control-input\" id=\"public\" value=\"2\" formControlName=\"private\" name=\"defaultExampleRadios\" mdbInput>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-control-label\" for=\"public\">public</label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"radio\" class=\"custom-control-input\" id=\"Both\" value=\"3\" formControlName=\"private\" name=\"defaultExampleRadios\"  mdbInput>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<label class=\"custom-control-label\" for=\"Both\">Both</label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"company-name\">Video Link*</label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fab fa-youtube\"></i>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"text\" id=\"video_link\" formControlName=\"video_link\" class=\"form-control\" id=\"video-link\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Posted by*</label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-user\"></i>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<select class=\"form-control\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<option id=\"opt\" value=\"Admin\">Admin</option>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</select>\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Category*</label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-list-alt\"></i>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<select (change)=\"getsubcategory()\" [(ngModel)]=\"catselect\" [ngModelOptions]=\"{standalone: true}\" class=\"form-control\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<option *ngFor=\"let categorylist of categorylists\" [ngValue]=\"categorylist.id\">{{categorylist.category_name}}</option>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</select>\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-md-6 col-lg-6\">\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"address-1\">Select Sub Category*</label>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<i class=\"fas fa-stream\"></i>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<select [(ngModel)]=\"subcatselect\" [ngModelOptions]=\"{standalone: true}\" class=\"form-control\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<option *ngFor=\"let subcategorylist of subcategorylists\" value=\"{{subcategorylist.id}}\">{{subcategorylist.sub_category_name}}</option>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</select>\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t\t\t\t\t\t\n\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-sm-12 col-12\">\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card card-purple\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card-header\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<h4>Post Content</h4>\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"collapse support-ticket-panel show\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"card-body p-0\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<textarea type=\"text\" id=\"ck_editor\" formControlName=\"ck_editor\" class=\"form-control\"></textarea>\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</div>\n\n\n\t\t\t\t\t\t\t\t\t\t\t<div class=\"col-12 col-lg-12 mt-2 mb-3\">\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"submit-btn-group fr\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<button class=\"btn btn-secondary mr-2\" (click)=\"cancel()\">Cancel</button>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<button type=\"submit\" value=\"submit\" (click)=\"postdata(videoform)\" class=\"btn btn-success\">Update Changes</button>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>\n\t\t\t\t</section>\n\t\t\t</div>\n\t\t\t</div>\n\t\t\t</form>\n\t\t\t<footer class=\"main-footer\">\n\t\t\t\t<div class=\"footer-center text-center pb-2 pt-2\">\n\t\t\t\t\tCopyright &copy; 2021 <a href=\"https://cal4care.com/\">Cal4care</a>\n\t\t\t\t\t<div class=\"bullet\"></div> All Rights Reservered\n\t\t\t\t</div>\n\t\t\t</footer>\n\t\t</div>\n\n\t</div>\n\t<script src=\"https://cdn.ckeditor.com/ckeditor5/29.1.0/classic/ckeditor.js\"></script>\n\t<script>\n\t\tClassicEditor\n\t\t\t.create(document.querySelector('#ck-editor'))\n\t\t\t.catch(error => {\n\t\t\t\tconsole.error(error);\n\t\t\t});\n\t</script>\n<!-- </body>\n\n</html> -->");

/***/ }),

/***/ "./src/app/app-knowledge-base/app-knowledge-base-routing.module.ts":
/*!*************************************************************************!*\
  !*** ./src/app/app-knowledge-base/app-knowledge-base-routing.module.ts ***!
  \*************************************************************************/
/*! exports provided: AppKnowledgeBaseRoutingModule */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "AppKnowledgeBaseRoutingModule", function() { return AppKnowledgeBaseRoutingModule; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/__ivy_ngcc__/fesm2015/core.js");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/__ivy_ngcc__/fesm2015/router.js");
/* harmony import */ var _create_category_create_category_component__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./create-category/create-category.component */ "./src/app/app-knowledge-base/create-category/create-category.component.ts");
/* harmony import */ var _video_upload_video_upload_component__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./video-upload/video-upload.component */ "./src/app/app-knowledge-base/video-upload/video-upload.component.ts");
/* harmony import */ var _edit_page_edit_page_component__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./edit-page/edit-page.component */ "./src/app/app-knowledge-base/edit-page/edit-page.component.ts");
/* harmony import */ var _displaypage_displaypage_component__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./displaypage/displaypage.component */ "./src/app/app-knowledge-base/displaypage/displaypage.component.ts");
/* harmony import */ var _uploadpage_uploadpage_component__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./uploadpage/uploadpage.component */ "./src/app/app-knowledge-base/uploadpage/uploadpage.component.ts");








// import { KnowledgebaseComponent } from './knowledgebase/knowledgebase.component';
// import { VideotutorialsComponent } from './videotutorials/videotutorials.component';
const routes = [
    {
        path: '', component: _displaypage_displaypage_component__WEBPACK_IMPORTED_MODULE_6__["DisplaypageComponent"]
    }, {
        path: 'create-category', component: _create_category_create_category_component__WEBPACK_IMPORTED_MODULE_3__["CreateCategoryComponent"]
    }, {
        path: 'editpage', component: _edit_page_edit_page_component__WEBPACK_IMPORTED_MODULE_5__["EditPageComponent"]
    }, {
        path: 'displaypage', component: _displaypage_displaypage_component__WEBPACK_IMPORTED_MODULE_6__["DisplaypageComponent"]
    }, {
        path: 'videoupload', component: _video_upload_video_upload_component__WEBPACK_IMPORTED_MODULE_4__["VideoUploadComponent"]
    }, {
        path: 'upload', component: _uploadpage_uploadpage_component__WEBPACK_IMPORTED_MODULE_7__["UploadpageComponent"]
    }
];
let AppKnowledgeBaseRoutingModule = class AppKnowledgeBaseRoutingModule {
};
AppKnowledgeBaseRoutingModule = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_1__["NgModule"])({
        imports: [_angular_router__WEBPACK_IMPORTED_MODULE_2__["RouterModule"].forChild(routes)],
        exports: [_angular_router__WEBPACK_IMPORTED_MODULE_2__["RouterModule"]],
    })
], AppKnowledgeBaseRoutingModule);



/***/ }),

/***/ "./src/app/app-knowledge-base/app-knowledge-base.module.ts":
/*!*****************************************************************!*\
  !*** ./src/app/app-knowledge-base/app-knowledge-base.module.ts ***!
  \*****************************************************************/
/*! exports provided: AppKnowledgeBaseModule */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "AppKnowledgeBaseModule", function() { return AppKnowledgeBaseModule; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/__ivy_ngcc__/fesm2015/core.js");
/* harmony import */ var _angular_common__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/common */ "./node_modules/@angular/common/__ivy_ngcc__/fesm2015/common.js");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/forms */ "./node_modules/@angular/forms/__ivy_ngcc__/fesm2015/forms.js");
/* harmony import */ var _app_knowledge_base_routing_module__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./app-knowledge-base-routing.module */ "./src/app/app-knowledge-base/app-knowledge-base-routing.module.ts");
/* harmony import */ var _safe_pipe__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./safe.pipe */ "./src/app/app-knowledge-base/safe.pipe.ts");
/* harmony import */ var _create_category_create_category_component__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./create-category/create-category.component */ "./src/app/app-knowledge-base/create-category/create-category.component.ts");
/* harmony import */ var _video_upload_video_upload_component__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./video-upload/video-upload.component */ "./src/app/app-knowledge-base/video-upload/video-upload.component.ts");
/* harmony import */ var _edit_page_edit_page_component__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./edit-page/edit-page.component */ "./src/app/app-knowledge-base/edit-page/edit-page.component.ts");
/* harmony import */ var _displaypage_displaypage_component__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./displaypage/displaypage.component */ "./src/app/app-knowledge-base/displaypage/displaypage.component.ts");
/* harmony import */ var _uploadpage_uploadpage_component__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./uploadpage/uploadpage.component */ "./src/app/app-knowledge-base/uploadpage/uploadpage.component.ts");
/* harmony import */ var _angular_material_chips__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @angular/material/chips */ "./node_modules/@angular/material/__ivy_ngcc__/fesm2015/chips.js");
/* harmony import */ var _angular_material_input__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! @angular/material/input */ "./node_modules/@angular/material/__ivy_ngcc__/fesm2015/input.js");
/* harmony import */ var _angular_material_icon__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! @angular/material/icon */ "./node_modules/@angular/material/__ivy_ngcc__/fesm2015/icon.js");



// import { EditorModule } from '@tinymce/tinymce-angular';
// import { BrowserModule } from '@angular/platform-browser';
// import { HttpClientModule } from '@angular/common/http';








// import { KnowledgebaseComponent } from './knowledgebase/knowledgebase.component';
// import { VideotutorialsComponent } from './videotutorials/videotutorials.component';



let AppKnowledgeBaseModule = class AppKnowledgeBaseModule {
};
AppKnowledgeBaseModule = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_1__["NgModule"])({
        declarations: [
            _safe_pipe__WEBPACK_IMPORTED_MODULE_5__["SafePipe"],
            _create_category_create_category_component__WEBPACK_IMPORTED_MODULE_6__["CreateCategoryComponent"],
            _video_upload_video_upload_component__WEBPACK_IMPORTED_MODULE_7__["VideoUploadComponent"],
            _edit_page_edit_page_component__WEBPACK_IMPORTED_MODULE_8__["EditPageComponent"],
            _displaypage_displaypage_component__WEBPACK_IMPORTED_MODULE_9__["DisplaypageComponent"],
            _uploadpage_uploadpage_component__WEBPACK_IMPORTED_MODULE_10__["UploadpageComponent"]
        ],
        imports: [
            _angular_common__WEBPACK_IMPORTED_MODULE_2__["CommonModule"],
            _app_knowledge_base_routing_module__WEBPACK_IMPORTED_MODULE_4__["AppKnowledgeBaseRoutingModule"],
            // CreateCategoryComponent,
            // VideoUploadComponent,
            // EditPageComponent,
            // DisplaypageComponent,
            // UploadpageComponent,
            // KnowledgebaseComponent,
            // VideotutorialsComponent
            //  HttpClientModule,
            _angular_forms__WEBPACK_IMPORTED_MODULE_3__["FormsModule"],
            _angular_forms__WEBPACK_IMPORTED_MODULE_3__["ReactiveFormsModule"],
            _angular_material_chips__WEBPACK_IMPORTED_MODULE_11__["MatChipsModule"],
            _angular_material_input__WEBPACK_IMPORTED_MODULE_12__["MatInputModule"], _angular_material_icon__WEBPACK_IMPORTED_MODULE_13__["MatIconModule"]
        ],
        providers: [],
    })
], AppKnowledgeBaseModule);



/***/ }),

/***/ "./src/app/app-knowledge-base/create-category/create-category.component.css":
/*!**********************************************************************************!*\
  !*** ./src/app/app-knowledge-base/create-category/create-category.component.css ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("\r\n  .mat-form-field { width: 100%; }\r\n  .mat-form-field-infix { border: 0; }\r\n  .mat-chip { word-break: break-word; }\r\n  .mat-chip-list-wrapper input.mat-input-element, .mat-chip-list-wrapper .mat-standard-chip {\r\n      background-color: #25D366;\r\n      color: #fff;\r\n   }\r\n  .mat-chip.mat-standard-chip { color: #ffffff; background-color: #3cb371; }\r\n  .mat-chip.mat-standard-chip .mat-chip-remove { color: #fff; opacity: 1; }\r\n  .mat-form-field-underline { border-top: 0; }\r\n  .box-shadow {  \r\n    padding: 0px 8px 0px 21px;\r\n    box-shadow: 0 0 8px 4px lightgrey;\r\n    width: 50%;\r\n    height: 28px;\r\n    display: block;\r\n    margin-top: 5px;\r\n    filter: invert(1);\r\n  }\r\n  .modal#ContractDetails .card { padding: 25px; color: #fff; border-radius: 5px; }\r\n  .modal#ContractDetails .card .collapse.show {  }\r\n  .modal#ContractDetails .card .card-header { border-bottom: 2px solid #fff; cursor: pointer; position: relative; background: #3abaf4; }\r\n  .modal#ContractDetails .card-body { border: 1px solid #e3e3e3; filter: invert(100%); }\r\n  .subcategory-badge i {\r\n  display: none;\r\n}\r\n  .subcategory-badge:hover i {\r\n  display: block;\r\n  color: red;\r\n  cursor: pointer;\r\n  font-weight: 10px;\r\n  float: right;\r\n  padding: 0 8px;\r\n}\r\n  .subcategory-badge:hover div {\r\n  display: none;\r\n \r\n}\r\n\r\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9hcHAvYXBwLWtub3dsZWRnZS1iYXNlL2NyZWF0ZS1jYXRlZ29yeS9jcmVhdGUtY2F0ZWdvcnkuY29tcG9uZW50LmNzcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiO0VBQ0Usa0JBQWtCLFdBQVcsRUFBRTtFQUMvQix3QkFBd0IsU0FBUyxFQUFFO0VBQ25DLFlBQVksc0JBQXNCLEVBQUU7RUFDcEM7TUFDSSx5QkFBeUI7TUFDekIsV0FBVztHQUNkO0VBQ0EsOEJBQThCLGNBQWMsRUFBRSx5QkFBeUIsRUFBRTtFQUN6RSwrQ0FBK0MsV0FBVyxFQUFFLFVBQVUsRUFBRTtFQUN6RSw0QkFBNEIsYUFBYSxFQUFFO0VBQzNDO0lBQ0UseUJBQXlCO0lBQ3pCLGlDQUFpQztJQUNqQyxVQUFVO0lBQ1YsWUFBWTtJQUNaLGNBQWM7SUFDZCxlQUFlO0lBQ2YsaUJBQWlCO0VBQ25CO0VBQ0YsK0JBQStCLGFBQWEsRUFBRSxXQUFXLEVBQUUsa0JBQWtCLEVBQUU7RUFDL0UsK0NBQStDO0VBQy9DLDRDQUE0Qyw2QkFBNkIsRUFBRSxlQUFlLEVBQUUsa0JBQWtCLEVBQUUsbUJBQW1CLEVBQUU7RUFDckksb0NBQW9DLHlCQUF5QixFQUFFLG9CQUFvQixFQUFFO0VBRXJGO0VBQ0UsYUFBYTtBQUNmO0VBRUE7RUFDRSxjQUFjO0VBQ2QsVUFBVTtFQUNWLGVBQWU7RUFDZixpQkFBaUI7RUFDakIsWUFBWTtFQUNaLGNBQWM7QUFDaEI7RUFBQztFQUNDLGFBQWE7O0FBRWYiLCJmaWxlIjoic3JjL2FwcC9hcHAta25vd2xlZGdlLWJhc2UvY3JlYXRlLWNhdGVnb3J5L2NyZWF0ZS1jYXRlZ29yeS5jb21wb25lbnQuY3NzIiwic291cmNlc0NvbnRlbnQiOlsiXHJcbiAgLm1hdC1mb3JtLWZpZWxkIHsgd2lkdGg6IDEwMCU7IH1cclxuICAubWF0LWZvcm0tZmllbGQtaW5maXggeyBib3JkZXI6IDA7IH1cclxuICAubWF0LWNoaXAgeyB3b3JkLWJyZWFrOiBicmVhay13b3JkOyB9XHJcbiAgLm1hdC1jaGlwLWxpc3Qtd3JhcHBlciBpbnB1dC5tYXQtaW5wdXQtZWxlbWVudCwgLm1hdC1jaGlwLWxpc3Qtd3JhcHBlciAubWF0LXN0YW5kYXJkLWNoaXAge1xyXG4gICAgICBiYWNrZ3JvdW5kLWNvbG9yOiAjMjVEMzY2O1xyXG4gICAgICBjb2xvcjogI2ZmZjtcclxuICAgfVxyXG4gICAubWF0LWNoaXAubWF0LXN0YW5kYXJkLWNoaXAgeyBjb2xvcjogI2ZmZmZmZjsgYmFja2dyb3VuZC1jb2xvcjogIzNjYjM3MTsgfVxyXG4gICAubWF0LWNoaXAubWF0LXN0YW5kYXJkLWNoaXAgLm1hdC1jaGlwLXJlbW92ZSB7IGNvbG9yOiAjZmZmOyBvcGFjaXR5OiAxOyB9XHJcbiAgLm1hdC1mb3JtLWZpZWxkLXVuZGVybGluZSB7IGJvcmRlci10b3A6IDA7IH1cclxuICAuYm94LXNoYWRvdyB7ICBcclxuICAgIHBhZGRpbmc6IDBweCA4cHggMHB4IDIxcHg7XHJcbiAgICBib3gtc2hhZG93OiAwIDAgOHB4IDRweCBsaWdodGdyZXk7XHJcbiAgICB3aWR0aDogNTAlO1xyXG4gICAgaGVpZ2h0OiAyOHB4O1xyXG4gICAgZGlzcGxheTogYmxvY2s7XHJcbiAgICBtYXJnaW4tdG9wOiA1cHg7XHJcbiAgICBmaWx0ZXI6IGludmVydCgxKTtcclxuICB9XHJcbi5tb2RhbCNDb250cmFjdERldGFpbHMgLmNhcmQgeyBwYWRkaW5nOiAyNXB4OyBjb2xvcjogI2ZmZjsgYm9yZGVyLXJhZGl1czogNXB4OyB9XHJcbi5tb2RhbCNDb250cmFjdERldGFpbHMgLmNhcmQgLmNvbGxhcHNlLnNob3cgeyAgfVxyXG4ubW9kYWwjQ29udHJhY3REZXRhaWxzIC5jYXJkIC5jYXJkLWhlYWRlciB7IGJvcmRlci1ib3R0b206IDJweCBzb2xpZCAjZmZmOyBjdXJzb3I6IHBvaW50ZXI7IHBvc2l0aW9uOiByZWxhdGl2ZTsgYmFja2dyb3VuZDogIzNhYmFmNDsgfVxyXG4ubW9kYWwjQ29udHJhY3REZXRhaWxzIC5jYXJkLWJvZHkgeyBib3JkZXI6IDFweCBzb2xpZCAjZTNlM2UzOyBmaWx0ZXI6IGludmVydCgxMDAlKTsgfVxyXG5cclxuLnN1YmNhdGVnb3J5LWJhZGdlIGkge1xyXG4gIGRpc3BsYXk6IG5vbmU7XHJcbn1cclxuXHJcbi5zdWJjYXRlZ29yeS1iYWRnZTpob3ZlciBpIHtcclxuICBkaXNwbGF5OiBibG9jaztcclxuICBjb2xvcjogcmVkO1xyXG4gIGN1cnNvcjogcG9pbnRlcjtcclxuICBmb250LXdlaWdodDogMTBweDtcclxuICBmbG9hdDogcmlnaHQ7XHJcbiAgcGFkZGluZzogMCA4cHg7XHJcbn0uc3ViY2F0ZWdvcnktYmFkZ2U6aG92ZXIgZGl2IHtcclxuICBkaXNwbGF5OiBub25lO1xyXG4gXHJcbn1cclxuIl19 */");

/***/ }),

/***/ "./src/app/app-knowledge-base/create-category/create-category.component.ts":
/*!*********************************************************************************!*\
  !*** ./src/app/app-knowledge-base/create-category/create-category.component.ts ***!
  \*********************************************************************************/
/*! exports provided: CreateCategoryComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "CreateCategoryComponent", function() { return CreateCategoryComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/__ivy_ngcc__/fesm2015/core.js");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/forms */ "./node_modules/@angular/forms/__ivy_ngcc__/fesm2015/forms.js");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/__ivy_ngcc__/fesm2015/router.js");
/* harmony import */ var sweetalert2__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! sweetalert2 */ "./node_modules/sweetalert2/dist/sweetalert2.all.js");
/* harmony import */ var sweetalert2__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(sweetalert2__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _services_service_service__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../services/service.service */ "./src/app/app-knowledge-base/services/service.service.ts");
/* harmony import */ var _angular_cdk_keycodes__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @angular/cdk/keycodes */ "./node_modules/@angular/cdk/__ivy_ngcc__/fesm2015/keycodes.js");







let CreateCategoryComponent = class CreateCategoryComponent {
    constructor(fb, serverService, router) {
        this.fb = fb;
        this.serverService = serverService;
        this.router = router;
        this.editsubcateogry = [];
        this.separatorKeysCodes = [_angular_cdk_keycodes__WEBPACK_IMPORTED_MODULE_6__["ENTER"], _angular_cdk_keycodes__WEBPACK_IMPORTED_MODULE_6__["COMMA"]];
        this.addcategory = this.fb.group({
            category_name: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
            description: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required]
        });
        this.addsubcategory = this.fb.group({
            subcategory_name: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
        });
    }
    ;
    editadd(event) {
        const value = (event.value || '').trim();
        const input = event.input;
        if (value) {
            this.editsubcateogry.push({ name: value });
            event.value = "";
        }
        if (input) {
            input.value = '';
        }
    }
    editremove(code) {
        const index = this.editsubcateogry.indexOf(code);
        if (index >= 0) {
            this.editsubcateogry.splice(index, 1);
        }
    }
    ngOnInit() {
        // Swal.close();    
        this.getcategory();
    }
    cancel1() {
        // alert('123')
        this.addcategory.reset();
    }
    cancel() {
        // alert('123')
        this.addsubcategory.reset();
        $(".form-control").val('');
        this.serverService.GoBack();
    }
    postD2(addcategory) {
        var category_name = addcategory.value.category_name;
        addcategory.value.description = addcategory.value.description.replace(/[\r\n]+/gm, " ");
        console.log(addcategory.value.description);
        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire({
            html: '<div style="display: flex;justify-content: center;"><div class="pong-loader"></div></div>',
            showCloseButton: false,
            showCancelButton: false,
            showConfirmButton: false,
            focusConfirm: false,
            background: 'transparent'
        });
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"category","category_name":"' + category_name + '","description":"' + addcategory.value.description + '"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.close();
            if (response.result.data == 1) {
                iziToast.success({
                    message: "Category created successfully",
                    position: 'topRight'
                });
                this.addcategory.reset();
                this.getcategory();
            }
            else if (response.result.data == 2) {
                iziToast.error({
                    message: "Category already exist",
                    position: 'topRight'
                });
            }
            else if (response.result.data == 3) {
                iziToast.error({
                    message: "Category Limit exceeds. only 16 allowed",
                    position: 'topRight'
                });
            }
        }, (error) => {
            console.log(error);
        });
        console.log(addcategory);
    }
    getcategory() {
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"selectcategory"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            console.log(response);
            if (response.status == true) {
                // console.log("asdf")
                this.categorylists = response.result.data;
                this.new_categorylists = [];
                this.categorylists.forEach(element => {
                    var splitted = [];
                    if (element.subcategory != null) {
                        splitted = element.subcategory.split(",");
                    }
                    this.new_categorylists.push({ category_name: element.category_name, subcategory: splitted, id: element.id, status: element.status });
                });
            }
        }, (error) => {
            console.log(error);
        });
    }
    postA2(addsubcategory) {
        var subcategory_name = addsubcategory.value.subcategory_name;
        if (this.catselect == '' || this.catselect == 0 || this.catselect == '0' || this.catselect == 'undefined') {
            iziToast.warning({
                message: "Please choose category",
                position: 'topRight'
            });
            return false;
        }
        if (subcategory_name == '' || subcategory_name == null || subcategory_name == 'null' || subcategory_name == 'undefined') {
            iziToast.warning({
                message: "Please Enter Sub category name",
                position: 'topRight'
            });
            return false;
        }
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"sub_category","sub_category_name":"' + subcategory_name + '","category_id":"' + this.catselect + '"}}';
        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire({
            html: '<div style="display: flex;justify-content: center;"><div class="pong-loader"></div></div>',
            showCloseButton: false,
            showCancelButton: false,
            showConfirmButton: false,
            focusConfirm: false,
            background: 'transparent',
        });
        this.serverService.sendserver(api_req).subscribe((response) => {
            sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.close();
            if (response.result.data == 1) {
                // Swal.close(); 
                iziToast.success({
                    message: "SubCategory created successfully",
                    position: 'topRight'
                });
                this.addsubcategory.reset();
                this.getcategory();
                //this.router.navigate(['/videoupload'])
                // Swal.close();
            }
            else if (response.result.data == 2) {
                iziToast.error({
                    message: "SubCategory already exist",
                    position: 'topRight'
                });
            }
            // Swal.close();
        }, (error) => {
            console.log(error);
        });
        console.log(addsubcategory);
    }
    deleteCategory(id) {
        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire({
            title: 'Are you sure?',
            text: "It will permanently remove all the article belongs to this category",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"delete_category","id":"' + id + '"}}	';
                this.serverService.sendserver(api_req).subscribe((response) => {
                    if (response.result.data == 1) {
                        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire('Deleted!', 'success');
                        this.getcategory();
                    }
                }, (error) => {
                    console.log(error);
                });
            }
        });
    }
    ToggleStatus(queue) {
        let data;
        let status;
        if (queue.status == '1') {
            data = 'Make category ' + queue.category_name + ' inactive ';
            status = '0';
        }
        else {
            data = 'Make category ' + queue.category_name + ' active ';
            status = '1';
        }
        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire({
            title: data,
            text: "It belongs to the whole category of articles",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"toggle_category_status","id":"' + queue.id + '","status":"' + status + '"}}	';
                this.serverService.sendserver(api_req).subscribe((response) => {
                    if (response.result.data == 1) {
                        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire('Updated!', 'success');
                        this.getcategory();
                    }
                }, (error) => {
                    console.log(error);
                });
            }
        });
    }
    edit_category(id) {
        // this.editsubcateogry=[];
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"editCategory","id":"' + id + '"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            console.log(response);
            if (response.status == true) {
                // console.log("asdf")
                // this.categorylists = response.result.data;
                $('#edit_category_name').val(response.result.data[0].category_name);
                $('#edit_category_description').val(response.result.data[0].description);
                $('#edit_category').modal('show');
                this.edit_cat_id = response.result.data[0].id;
                // if(response.result.data[0].subcategory !='' &&response.result.data[0].subcategory !=null){
                // var edit_splits = response.result.data[0].subcategory.split(',');
                // console.log(edit_splits);
                // edit_splits.forEach(element => {
                //   this.editsubcateogry.push({ name: element });
                // });
                // }
            }
        }, (error) => {
            console.log(error);
        });
        console.log();
    }
    Updatecategory() {
        let name = $('#edit_category_name').val();
        let description = $('#edit_category_description').val();
        if (description == '' || name == '') {
            iziToast.warning({
                message: "Please enter the details",
                position: 'topRight'
            });
            return false;
        }
        description = description.replace(/[\r\n]+/gm, " ");
        // var new_array = [];
        // this.editsubcateogry.forEach(element => {
        //   new_array.push(element.name);
        // });
        // var Supcategory = new_array.join(",");
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"Updatecategory","id":"' + this.edit_cat_id + '","category_name":"' + name + '","description":"' + description + '"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            console.log(response);
            if (response.result.data == true) {
                $('#edit_category').modal('hide');
                sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire('Updated!', 'success');
                this.getcategory();
            }
            else {
                sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire('Oops!', 'error');
            }
        }, (error) => {
            console.log(error);
        });
    }
    RemoveSubcat(id) {
        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire({
            title: 'Are you sure?',
            text: "It will permanently remove all the article belongs to this Subcategory",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"delete_sub_category","name":"' + id + '"}}	';
                this.serverService.sendserver(api_req).subscribe((response) => {
                    if (response.result.data == 1) {
                        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire('Deleted!', 'success');
                        this.getcategory();
                    }
                }, (error) => {
                    console.log(error);
                });
            }
        });
    }
    UpdateSubcat(id) {
        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire({
            title: 'Submit your subcategoryname',
            input: 'text',
            showCancelButton: true,
            confirmButtonText: 'Update',
            showLoaderOnConfirm: true,
            inputValue: id,
            allowOutsideClick: () => !sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                let update_subcat = result.value;
                if (update_subcat == '' || update_subcat == null) {
                    iziToast.warning({
                        message: "Please enter the value",
                        position: "topRight"
                    });
                    return false;
                }
                let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"update_sub_category","name":"' + id + '","update_name":"' + update_subcat + '"}}	';
                this.serverService.sendserver(api_req).subscribe((response) => {
                    if (response.result.data == 1) {
                        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire('Updated!', 'success');
                        this.getcategory();
                    }
                    else {
                        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire('oops!', 'error');
                    }
                }, (error) => {
                    console.log(error);
                });
            }
        });
        // Swal.fire({
        //   title: 'Are you sure?',
        //   text: "It will permanently remove all the article belongs to this Subcategory",
        //   icon: 'warning',
        //   showCancelButton: true,
        //   confirmButtonColor: '#3085d6',
        //   cancelButtonColor: '#d33',
        //   confirmButtonText: 'Yes, delete it!'
        // }).then((result) => {
        //   if (result.value) {
        //   }
        // })
    }
};
CreateCategoryComponent.ctorParameters = () => [
    { type: _angular_forms__WEBPACK_IMPORTED_MODULE_2__["FormBuilder"] },
    { type: _services_service_service__WEBPACK_IMPORTED_MODULE_5__["ServiceService"] },
    { type: _angular_router__WEBPACK_IMPORTED_MODULE_3__["Router"] }
];
CreateCategoryComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_1__["Component"])({
        selector: 'app-create-category',
        template: Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__importDefault"])(__webpack_require__(/*! raw-loader!./create-category.component.html */ "./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/create-category/create-category.component.html")).default,
        styles: [Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__importDefault"])(__webpack_require__(/*! ./create-category.component.css */ "./src/app/app-knowledge-base/create-category/create-category.component.css")).default]
    })
], CreateCategoryComponent);



/***/ }),

/***/ "./src/app/app-knowledge-base/displaypage/displaypage.component.css":
/*!**************************************************************************!*\
  !*** ./src/app/app-knowledge-base/displaypage/displaypage.component.css ***!
  \**************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IiIsImZpbGUiOiJzcmMvYXBwL2FwcC1rbm93bGVkZ2UtYmFzZS9kaXNwbGF5cGFnZS9kaXNwbGF5cGFnZS5jb21wb25lbnQuY3NzIn0= */");

/***/ }),

/***/ "./src/app/app-knowledge-base/displaypage/displaypage.component.ts":
/*!*************************************************************************!*\
  !*** ./src/app/app-knowledge-base/displaypage/displaypage.component.ts ***!
  \*************************************************************************/
/*! exports provided: DisplaypageComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DisplaypageComponent", function() { return DisplaypageComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/__ivy_ngcc__/fesm2015/core.js");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/__ivy_ngcc__/fesm2015/router.js");
/* harmony import */ var sweetalert2__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! sweetalert2 */ "./node_modules/sweetalert2/dist/sweetalert2.all.js");
/* harmony import */ var sweetalert2__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(sweetalert2__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _services_service_service__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../services/service.service */ "./src/app/app-knowledge-base/services/service.service.ts");





let DisplaypageComponent = class DisplaypageComponent {
    constructor(serverService, router, route) {
        this.serverService = serverService;
        this.router = router;
        this.route = route;
        this.recordNotFound = false;
        this.catItemsnotFound = false;
    }
    ngOnInit() {
        this.uadmin_id = localStorage.getItem('userId');
        this.admin_id = localStorage.getItem('admin_id');
        this.getcategory();
        this.selectedID = this.route.snapshot.queryParamMap.get('selectedID');
    }
    getcategory() {
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"selectcategory"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            console.log(response);
            if (response.status == true) {
                this.category_list = response.result.data;
                if (this.selectedID == 'undefined' || this.selectedID == undefined || this.selectedID == null) {
                    let id = response.result.data[0].id;
                    let Jqueryid = 'panel-body_' + id;
                    // $('.accordion-body').removeClass('show'); 
                    // setTimeout(() => {
                    // $('#'+id).addClass('collapse'); 
                    // $('#'+id).addClass('show'); 
                    // }, 1000);
                    this.ShowSinglecategory(Jqueryid, id);
                }
                if (this.selectedID != 'undefined' && this.selectedID != undefined && this.selectedID != null) {
                    this.selectedID = atob(this.selectedID);
                    let id = 'panel-body_' + this.selectedID;
                    //  $('.accordion-body').removeClass('show'); 
                    //  setTimeout(() => {
                    //  $('#'+id).addClass('collapse'); 
                    //  $('#'+id).addClass('show'); 
                    //  }, 100);
                    this.ShowSinglecategory(id, this.selectedID);
                }
            }
        }, (error) => {
            console.log(error);
        });
    }
    ShowSinglecategory(id, category) {
        if (!$('#' + id).hasClass('show')) { //restrict if already open
            let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"ShowSinglecategory","id":"' + category + '"}}';
            this.serverService.sendserver(api_req).subscribe((response) => {
                console.log(response);
                if (response.status == "true") {
                    this.category_items = response.data;
                    if (this.category_items < 1) {
                        this.catItemsnotFound = true;
                    }
                    else {
                        this.catItemsnotFound = false;
                    }
                }
            }, (error) => {
                console.log(error);
            });
        }
        //To show the single id and hide others
        $('.accordion-body').removeClass('show');
        setTimeout(() => {
            $('#' + id).addClass('collapse');
            $('#' + id).addClass('show');
        }, 100);
    }
    displaycat() {
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"display"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            if (response.status == 'true') {
                this.category_list = response.data;
                if (this.category_list.length == 0)
                    this.recordNotFound = true;
            }
        }, (error) => {
            console.log(error);
        });
    }
    editpost(id) {
        var edit_id = btoa(id); // Base64 encode the String
        this.router.navigate(['/kb/editpage'], { queryParams: { id: edit_id } });
    }
    // editpost(id){
    //   let api_req:any = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"editpage","id":"'+id+'"}}	';
    // this.serverService.sendServer(api_req).subscribe((response:any) => {
    //   if(response.result.data==1){
    //     this.router.navigate(['/editpage'])
    //   }
    // }, 
    // (error)=>{
    //     console.log(error);
    // });
    // }
    deletepost(id) {
        sweetalert2__WEBPACK_IMPORTED_MODULE_3___default.a.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"delete","id":"' + id + '"}}	';
                this.serverService.sendserver(api_req).subscribe((response) => {
                    if (response.result.data == 1) {
                        sweetalert2__WEBPACK_IMPORTED_MODULE_3___default.a.fire('Deleted!', 'success');
                        this.getcategory();
                    }
                }, (error) => {
                    console.log(error);
                });
            }
        });
    }
    ToggleCollapse(id) {
    }
};
DisplaypageComponent.ctorParameters = () => [
    { type: _services_service_service__WEBPACK_IMPORTED_MODULE_4__["ServiceService"] },
    { type: _angular_router__WEBPACK_IMPORTED_MODULE_2__["Router"] },
    { type: _angular_router__WEBPACK_IMPORTED_MODULE_2__["ActivatedRoute"] }
];
DisplaypageComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_1__["Component"])({
        selector: 'app-displaypage',
        template: Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__importDefault"])(__webpack_require__(/*! raw-loader!./displaypage.component.html */ "./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/displaypage/displaypage.component.html")).default,
        styles: [Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__importDefault"])(__webpack_require__(/*! ./displaypage.component.css */ "./src/app/app-knowledge-base/displaypage/displaypage.component.css")).default]
    })
], DisplaypageComponent);



/***/ }),

/***/ "./src/app/app-knowledge-base/edit-page/edit-page.component.css":
/*!**********************************************************************!*\
  !*** ./src/app/app-knowledge-base/edit-page/edit-page.component.css ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IiIsImZpbGUiOiJzcmMvYXBwL2FwcC1rbm93bGVkZ2UtYmFzZS9lZGl0LXBhZ2UvZWRpdC1wYWdlLmNvbXBvbmVudC5jc3MifQ== */");

/***/ }),

/***/ "./src/app/app-knowledge-base/edit-page/edit-page.component.ts":
/*!*********************************************************************!*\
  !*** ./src/app/app-knowledge-base/edit-page/edit-page.component.ts ***!
  \*********************************************************************/
/*! exports provided: EditPageComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "EditPageComponent", function() { return EditPageComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/__ivy_ngcc__/fesm2015/core.js");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/forms */ "./node_modules/@angular/forms/__ivy_ngcc__/fesm2015/forms.js");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/__ivy_ngcc__/fesm2015/router.js");
/* harmony import */ var _services_service_service__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../services/service.service */ "./src/app/app-knowledge-base/services/service.service.ts");





let EditPageComponent = class EditPageComponent {
    constructor(fb, serverService, route, router) {
        this.fb = fb;
        this.serverService = serverService;
        this.route = route;
        this.router = router;
        this.param = this.route.snapshot.queryParamMap.get('id');
        var decodedString = atob(this.param);
        this.param = decodedString;
        this.editform = new _angular_forms__WEBPACK_IMPORTED_MODULE_2__["FormGroup"]({
            // post_title: new FormControl(),
            // ck_editor: new FormControl(),
            // video_link: new FormControl(),
            subcatselect: new _angular_forms__WEBPACK_IMPORTED_MODULE_2__["FormControl"](),
            catselect: new _angular_forms__WEBPACK_IMPORTED_MODULE_2__["FormControl"](),
            // status:new FormControl(),
            displaytype: new _angular_forms__WEBPACK_IMPORTED_MODULE_2__["FormControl"](),
        });
    }
    ngOnInit() {
        console.log(this.editform);
        this.initTiny();
        this.getedit();
        this.getcategory();
    }
    getcategory() {
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"selectcategory"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            console.log(response);
            if (response.status == true) {
                this.categorylists = response.result.data;
            }
        }, (error) => {
            console.log(error);
        });
        console.log();
    }
    getsubcategory() {
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"select_sub_category","category_id":"' + this.catselect + '"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            console.log(response);
            if (response.status == true) {
                // console.log("asdf")
                this.subcategorylists = response.result.data;
            }
        }, (error) => {
            console.log(error);
        });
        console.log();
    }
    getedit() {
        //let api_req:any = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"editpage","id":"'+this.param+'"}}';
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"editpage_document","id":"' + this.param + '"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            console.log(response);
            if (response.status == true) {
                // console.log("asdf")
                console.log(this.param);
                this.editlist = response.result.data[0];
                this.catselect = response.result.data[0].category_id;
                this.post_status = this.editlist.status;
                console.log('Cat  ' + this.catselect);
                this.getsubcategory();
                $('#edit_post_title').val(this.editlist.post_title);
                tinymce.get('edit_ck_editor').setContent(atob(this.editlist.post_content));
                if (response.result.data[0].featured == '1')
                    $("#edit_featured").prop('checked', true);
                this.editform.setValue({
                    // 'post_title':this.editlist.post_title,
                    'catselect': this.editlist.category_id,
                    'subcatselect': this.editlist.subcat_id,
                    'displaytype': this.editlist.display_type,
                });
            }
        }, (error) => {
            console.log(error);
        });
        console.log();
    }
    update(editform) {
        var post_title = $('#edit_post_title').val();
        var catselect = editform.value.catselect;
        var subcatselect = editform.value.subcatselect;
        var displaytype = editform.value.displaytype;
        let featured = '0';
        if ($("#edit_featured").prop('checked')) {
            featured = '1';
        }
        ;
        // var status =  $('#edit_status').val();
        var status = '0';
        if ($('#edit_status').prop('checked')) {
            status = '1';
        }
        var ck_editor = btoa(tinymce.activeEditor.getContent());
        // var ck_editor = btoa(editform.value.ck_editor);
        if (catselect == '' || subcatselect == '' || post_title == "") {
            iziToast.warning({
                message: 'Please fill the require fields',
                position: 'topRight'
            });
            return false;
        }
        // let api_req:any = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"edit","post_title":"'+post_title+'","video_link":"'+video_link+'","status":"'+status+'","category_id":"'+catselect+'","subcat_id":"'+subcatselect+'","post_content":"'+ck_editor+'","id":"'+this.param+'"}}';
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"update_doc_post","post_title":"' + post_title + '","status":"' + status + '","category_id":"' + catselect + '","subcat_id":"' + subcatselect + '","post_content":"' + ck_editor + '","id":"' + this.param + '","display_type":"' + displaytype + '","featured":"' + featured + '"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            console.log(api_req);
            // return false;
            console.log(response);
            if (response.result.data == 1) {
                this.router.navigate(['/kb/displaypage'], { queryParams: { selectedID: btoa(this.catselect) } });
            }
        }, (error) => {
            console.log(error);
        });
        console.log(editform);
    }
    initTiny() {
        var richTextArea_id = 'edit_ck_editor';
        tinymce.init({
            selector: '#edit_ck_editor',
            height: 500,
            plugins: 'advlist autolink formatpainter lists link  image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste  wordcount autolink lists media table',
            toolbar: 'undo redo |fullscreen|forecolor backcolor| formatselect | bold italic | \ undo redo | link image file| code | \
        alignleft aligncenter alignright alignjustify | \
        bullist numlist outdent indent | autoresize',
            paste_data_images: true,
            images_upload_url: 'upload.php',
            automatic_uploads: false,
            default_link_target: "_blank",
            extended_valid_elements: "a[href|target=_blank]",
            link_assume_external_targets: true,
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', 'upload.php');
                xhr.onload = function () {
                    var json;
                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.file_path != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.file_path);
                };
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            },
        });
        if (tinymce.editors.length > 0) {
            //tinymce.execCommand('mceFocus', true, richTextArea_id );       
            tinymce.execCommand('mceRemoveEditor', true, richTextArea_id);
            tinymce.execCommand('mceAddEditor', true, richTextArea_id);
        }
    }
    goback() {
        this.serverService.GoBack();
    }
};
EditPageComponent.ctorParameters = () => [
    { type: _angular_forms__WEBPACK_IMPORTED_MODULE_2__["FormBuilder"] },
    { type: _services_service_service__WEBPACK_IMPORTED_MODULE_4__["ServiceService"] },
    { type: _angular_router__WEBPACK_IMPORTED_MODULE_3__["ActivatedRoute"] },
    { type: _angular_router__WEBPACK_IMPORTED_MODULE_3__["Router"] }
];
EditPageComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_1__["Component"])({
        selector: 'app-edit-page',
        template: Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__importDefault"])(__webpack_require__(/*! raw-loader!./edit-page.component.html */ "./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/edit-page/edit-page.component.html")).default,
        styles: [Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__importDefault"])(__webpack_require__(/*! ./edit-page.component.css */ "./src/app/app-knowledge-base/edit-page/edit-page.component.css")).default]
    })
], EditPageComponent);



/***/ }),

/***/ "./src/app/app-knowledge-base/safe.pipe.ts":
/*!*************************************************!*\
  !*** ./src/app/app-knowledge-base/safe.pipe.ts ***!
  \*************************************************/
/*! exports provided: SafePipe */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SafePipe", function() { return SafePipe; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/__ivy_ngcc__/fesm2015/core.js");
/* harmony import */ var _angular_platform_browser__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/platform-browser */ "./node_modules/@angular/platform-browser/__ivy_ngcc__/fesm2015/platform-browser.js");



let SafePipe = class SafePipe {
    constructor(sanitizer) {
        this.sanitizer = sanitizer;
    }
    transform(url) {
        return this.sanitizer.bypassSecurityTrustResourceUrl(url);
    }
};
SafePipe.ctorParameters = () => [
    { type: _angular_platform_browser__WEBPACK_IMPORTED_MODULE_2__["DomSanitizer"] }
];
SafePipe = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_1__["Pipe"])({
        name: 'safe'
    })
], SafePipe);



/***/ }),

/***/ "./src/app/app-knowledge-base/services/service.service.ts":
/*!****************************************************************!*\
  !*** ./src/app/app-knowledge-base/services/service.service.ts ***!
  \****************************************************************/
/*! exports provided: ServiceService */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ServiceService", function() { return ServiceService; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/__ivy_ngcc__/fesm2015/core.js");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/common/http */ "./node_modules/@angular/common/__ivy_ngcc__/fesm2015/http.js");
/* harmony import */ var _angular_common__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/common */ "./node_modules/@angular/common/__ivy_ngcc__/fesm2015/common.js");




let ServiceService = class ServiceService {
    constructor(http, Location) {
        this.http = http;
        this.Location = Location;
    }
    getDocument(filename) {
        throw new Error('Method not implemented.');
    }
    sendserver(postData) {
        const httpOptions = {
            headers: new _angular_common_http__WEBPACK_IMPORTED_MODULE_2__["HttpHeaders"]({
                'Content-Type': 'application/json'
            })
        };
        // return this.http.post("http://localhost/helpdesk_apis/api-helpdesk/v1.0/index.php",postData,httpOptions);
        return this.http.post("https://omnitickets.mconnectapps.com/api/v1.0/index.php", postData, httpOptions);
    }
    GoBack() {
        return this.Location.back();
    }
};
ServiceService.ctorParameters = () => [
    { type: _angular_common_http__WEBPACK_IMPORTED_MODULE_2__["HttpClient"] },
    { type: _angular_common__WEBPACK_IMPORTED_MODULE_3__["Location"] }
];
ServiceService = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_1__["Injectable"])({
        providedIn: 'root'
    })
], ServiceService);



/***/ }),

/***/ "./src/app/app-knowledge-base/uploadpage/uploadpage.component.css":
/*!************************************************************************!*\
  !*** ./src/app/app-knowledge-base/uploadpage/uploadpage.component.css ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IiIsImZpbGUiOiJzcmMvYXBwL2FwcC1rbm93bGVkZ2UtYmFzZS91cGxvYWRwYWdlL3VwbG9hZHBhZ2UuY29tcG9uZW50LmNzcyJ9 */");

/***/ }),

/***/ "./src/app/app-knowledge-base/uploadpage/uploadpage.component.ts":
/*!***********************************************************************!*\
  !*** ./src/app/app-knowledge-base/uploadpage/uploadpage.component.ts ***!
  \***********************************************************************/
/*! exports provided: UploadpageComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "UploadpageComponent", function() { return UploadpageComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/__ivy_ngcc__/fesm2015/core.js");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/forms */ "./node_modules/@angular/forms/__ivy_ngcc__/fesm2015/forms.js");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/__ivy_ngcc__/fesm2015/router.js");
/* harmony import */ var sweetalert2__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! sweetalert2 */ "./node_modules/sweetalert2/dist/sweetalert2.all.js");
/* harmony import */ var sweetalert2__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(sweetalert2__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _services_service_service__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../services/service.service */ "./src/app/app-knowledge-base/services/service.service.ts");






let UploadpageComponent = class UploadpageComponent {
    constructor(fb, serverService, router) {
        this.fb = fb;
        this.serverService = serverService;
        this.router = router;
        this.uploadform = this.fb.group({
            post_title: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
            status: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
            ck_editor: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
            display_type: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
            Quote_content: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
            private: new _angular_forms__WEBPACK_IMPORTED_MODULE_2__["FormControl"](),
            file: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required]
        });
    }
    ngOnInit() {
        this.initTiny();
        this.getcategory();
        this.getsubcategory();
        this.cancel();
        // Swal.fire({
        //   html:
        //     '<div style="display: flex;justify-content: center;"><div class="pong-loader"></div></div>',
        // showCloseButton: false,
        //   showCancelButton: false,
        //   showConfirmButton: false,
        //   focusConfirm: false,
        //   background: 'transparent',
        // });
        // Swal.close();
    }
    getcategory() {
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"selectcategory"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            console.log(response);
            if (response.status == true) {
                // console.log("asdf")
                this.categorylists = response.result.data;
            }
        }, (error) => {
            console.log(error);
        });
        console.log();
    }
    getsubcategory() {
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"select_sub_category","category_id":"' + this.catselect + '"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            console.log(response);
            if (response.status == true) {
                // console.log("asdf")
                this.subcategorylists = response.result.data;
            }
        }, (error) => {
            console.log(error);
        });
        console.log();
    }
    addcat() {
        this.router.navigate(['/kb/create-category']);
    }
    cancel() {
        // console.log(this.uploadform.value)
        // this.uploadform.reset();
        $("#post_title").val("");
        $("#catselect").val("");
        $("#subcatselect").val("");
        $("#quote_content").val("");
        $("#opt").val("");
        tinymce.activeEditor.setContent('');
        //  $(".form-control").val("");
    }
    postupload(uploadform) {
        // var form = document.getElementById('uploadform');
        var display_type = uploadform.value.private;
        // return false;
        var filename = document.getElementById('file').files[0];
        var status;
        if ($("#status_check").is(":checked") == true) {
            status = 1;
        }
        else {
            status = 0;
        }
        // let display_type:any = $("#radio").val();
        let post_title = $("#post_title").val();
        post_title = post_title.replace(/-/g, ' ').replace(/ +/g, ' ').trim(); //Need to add - instead of space for SEO
        var category_id = $("#catselect").val();
        let subcat_id = $("#subcatselect").val();
        let quote_content = '';
        let post_by = $("#opt").val();
        let featured = '0';
        if ($("#featured").prop('checked')) {
            featured = '1';
        }
        ;
        var post_content = btoa(tinymce.activeEditor.getContent());
        if (display_type == '' || display_type == 'null' || display_type == null) {
            iziToast.error({
                message: "Please Choose Display type",
                position: 'topRight'
            });
            return false;
        }
        if (post_title == '' || subcat_id == '' || subcat_id == 'undefined' || subcat_id == 'null' || subcat_id == null || category_id == '' || post_content == '') {
            iziToast.error({
                message: "Please fill all the fieds",
                position: 'topRight'
            });
            return false;
        }
        // if(!filename){
        //   iziToast.error({
        //     message: "Please upload the file",
        //     position: 'topRight'
        //   });
        //      return false;
        // }
        console.log(filename);
        var fileToUpload = new FormData();
        fileToUpload.append("filename", filename);
        fileToUpload.append("post_title", post_title);
        fileToUpload.append("display_type", display_type);
        fileToUpload.append("quote_content", quote_content);
        fileToUpload.append("status", status);
        fileToUpload.append("category_id", this.catselect);
        fileToUpload.append("subcat_id", subcat_id);
        fileToUpload.append("post_by", post_by);
        fileToUpload.append("post_content", post_content);
        fileToUpload.append("featured", featured);
        fileToUpload.append("action", "kb_file_upload");
        // console.log(display_type)
        // return false;
        // dt = '{"action":"upload","post_title":"' + post_title + '","quote_content":"'+quote_content+'","status":"'+status+'","category_id":"'+category_id+'","subcat_id":"'+subcat_id+'","post_content":"'+post_content+'"}';
        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire({
            html: '<div style="display: flex;justify-content: center;"><div class="pong-loader"></div></div>',
            showCloseButton: false,
            showCancelButton: false,
            showConfirmButton: false,
            focusConfirm: false,
            background: 'transparent',
        });
        var self = this;
        $.ajax({
            type: 'POST',
            url: "https://omnitickets.mconnectapps.com/api/v1.0/index_new.php",
            data: fileToUpload,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                //  alert(data);
                console.log(data);
                var result = JSON.parse(data);
                if (result.data == 2) {
                    sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.close();
                    sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire({
                        title: 'oops',
                        icon: 'success',
                        text: 'Post title not allowed try different one',
                        confirmButtonColor: '#3085d6',
                    });
                }
                else if (result.data == true) {
                    sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.close();
                    sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire({
                        title: 'Updated',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                    });
                    self.router.navigate(['/kb/displaypage'], { queryParams: { selectedID: btoa(self.catselect) } });
                    //   window.location.href = "http://localhost/new2/create-category.php";
                    //   $(data).addClass("done");
                }
                else {
                    if (result.status == "400") {
                        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire({
                            title: 'oops',
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                        });
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr);
            }
        });
    }
    initTiny() {
        var richTextArea_id = 'ck_editor';
        tinymce.init({
            selector: '#ck_editor',
            height: 500,
            plugins: 'advlist autolink formatpainter lists link  image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste  wordcount autolink lists media table',
            toolbar: 'undo redo |fullscreen|forecolor backcolor| formatselect | bold italic | \ undo redo | link image file| code | \
        alignleft aligncenter alignright alignjustify | \
        bullist numlist outdent indent | autoresize',
            paste_data_images: true,
            images_upload_url: 'upload.php',
            automatic_uploads: false,
            default_link_target: "_blank",
            extended_valid_elements: "a[href|target=_blank]",
            link_assume_external_targets: true,
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', 'upload.php');
                xhr.onload = function () {
                    var json;
                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.file_path != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.file_path);
                };
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            },
        });
        if (tinymce.editors.length > 0) {
            //tinymce.execCommand('mceFocus', true, richTextArea_id );       
            tinymce.execCommand('mceRemoveEditor', true, richTextArea_id);
            tinymce.execCommand('mceAddEditor', true, richTextArea_id);
        }
    }
};
UploadpageComponent.ctorParameters = () => [
    { type: _angular_forms__WEBPACK_IMPORTED_MODULE_2__["FormBuilder"] },
    { type: _services_service_service__WEBPACK_IMPORTED_MODULE_5__["ServiceService"] },
    { type: _angular_router__WEBPACK_IMPORTED_MODULE_3__["Router"] }
];
UploadpageComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_1__["Component"])({
        selector: 'app-uploadpage',
        template: Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__importDefault"])(__webpack_require__(/*! raw-loader!./uploadpage.component.html */ "./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/uploadpage/uploadpage.component.html")).default,
        styles: [Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__importDefault"])(__webpack_require__(/*! ./uploadpage.component.css */ "./src/app/app-knowledge-base/uploadpage/uploadpage.component.css")).default]
    })
], UploadpageComponent);



/***/ }),

/***/ "./src/app/app-knowledge-base/video-upload/video-upload.component.css":
/*!****************************************************************************!*\
  !*** ./src/app/app-knowledge-base/video-upload/video-upload.component.css ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IiIsImZpbGUiOiJzcmMvYXBwL2FwcC1rbm93bGVkZ2UtYmFzZS92aWRlby11cGxvYWQvdmlkZW8tdXBsb2FkLmNvbXBvbmVudC5jc3MifQ== */");

/***/ }),

/***/ "./src/app/app-knowledge-base/video-upload/video-upload.component.ts":
/*!***************************************************************************!*\
  !*** ./src/app/app-knowledge-base/video-upload/video-upload.component.ts ***!
  \***************************************************************************/
/*! exports provided: VideoUploadComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "VideoUploadComponent", function() { return VideoUploadComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/__ivy_ngcc__/fesm2015/core.js");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/forms */ "./node_modules/@angular/forms/__ivy_ngcc__/fesm2015/forms.js");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/__ivy_ngcc__/fesm2015/router.js");
/* harmony import */ var sweetalert2__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! sweetalert2 */ "./node_modules/sweetalert2/dist/sweetalert2.all.js");
/* harmony import */ var sweetalert2__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(sweetalert2__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _services_service_service__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../services/service.service */ "./src/app/app-knowledge-base/services/service.service.ts");






let VideoUploadComponent = class VideoUploadComponent {
    constructor(fb, serverService, router) {
        this.fb = fb;
        this.serverService = serverService;
        this.router = router;
        this.videoform = this.fb.group({
            post_title: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
            status: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
            public: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
            private: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
            Both: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
            video_link: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required],
            ck_editor: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_2__["Validators"].required]
        });
        // this.editor = this.fb.group({
        //   ck_editor: ['',Validators.required]
        // })  
    }
    ngOnInit() {
        this.getcategory();
        this.initTiny();
    }
    // editor(){
    //   console.log(this.ck_editor)
    // }
    getcategory() {
        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.close();
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"selectcategory"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            console.log(response);
            if (response.status == true) {
                // console.log("asdf")
                this.categorylists = response.result.data;
            }
        }, (error) => {
            console.log(error);
        });
        console.log();
    }
    getsubcategory() {
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"select_sub_category","category_id":"' + this.catselect + '"}}';
        this.serverService.sendserver(api_req).subscribe((response) => {
            console.log(response);
            if (response.status == true) {
                // console.log("asdf")
                this.subcategorylists = response.result.data;
            }
        }, (error) => {
            console.log(error);
        });
        console.log();
    }
    postdata(videoform) {
        //  console.log(btoa(tinymce.activeEditor.getContent()));
        //  let post = btoa(tinymce.activeEditor.getContent());
        //  console.log(post)
        //   return false;
        var post_title = videoform.value.post_title;
        var status = videoform.value.status;
        // let check : any;
        if (status == true) {
            status = 1;
        }
        else {
            status = 0;
        }
        var display_type = videoform.value.private;
        var post_by = $('#opt').val();
        var video_link = videoform.value.video_link;
        var ck_editor = btoa(tinymce.activeEditor.getContent());
        let api_req = '{"operation": "category","moduleType": "category","api_type": "web","element_data": {"action":"post","post_title":"' + post_title + '","video_link":"' + video_link + '","status":"' + status + '","category_id":"' + this.catselect + '","subcat_id":"' + this.subcatselect + '","post_content":"' + ck_editor + '","display_type":"' + display_type + '","post_by":"' + post_by + '"}}';
        sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.fire({
            html: '<div style="display: flex;justify-content: center;"><div class="pong-loader"></div></div>',
            showCloseButton: false,
            showCancelButton: false,
            showConfirmButton: false,
            focusConfirm: false,
            background: 'transparent',
        });
        this.serverService.sendserver(api_req).subscribe((response) => {
            //   console.log(api_req)
            // return false;
            sweetalert2__WEBPACK_IMPORTED_MODULE_4___default.a.close();
            if (response.result.data == 1) {
                this.posts = response.result.data;
                iziToast.success({
                    message: "Video link added",
                    position: 'topRight'
                });
                this.router.navigate(['/kb/displaypage']);
            }
        }, (error) => {
            console.log(error);
        });
        console.log(videoform);
    }
    cancel() {
        this.videoform.reset();
        $('.form-control').val('');
        $('#ck_editor').val('');
        tinymce.activeEditor.setContent('');
    }
    initTiny() {
        tinymce.init({
            selector: 'textarea',
            plugins: 'advlist autolink lists link  image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount autolink lists media table',
            toolbar: 'undo redo| fullscreen | formatselect | fontselect | fontsizeselect| bold italic | \ undo redo | link image file| code | \
      alignleft aligncenter alignright alignjustify | \
      bullist numlist outdent indent | help',
            content_style: 'body {font-size: 10pt;font-family: Verdana;}',
            images_upload_url: 'upload.php',
            automatic_uploads: false,
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', 'upload.php');
                xhr.onload = function () {
                    var json;
                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.file_path != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.file_path);
                };
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            },
        });
    }
};
VideoUploadComponent.ctorParameters = () => [
    { type: _angular_forms__WEBPACK_IMPORTED_MODULE_2__["FormBuilder"] },
    { type: _services_service_service__WEBPACK_IMPORTED_MODULE_5__["ServiceService"] },
    { type: _angular_router__WEBPACK_IMPORTED_MODULE_3__["Router"] }
];
VideoUploadComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_1__["Component"])({
        selector: 'app-video-upload',
        template: Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__importDefault"])(__webpack_require__(/*! raw-loader!./video-upload.component.html */ "./node_modules/raw-loader/dist/cjs.js!./src/app/app-knowledge-base/video-upload/video-upload.component.html")).default,
        styles: [Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__importDefault"])(__webpack_require__(/*! ./video-upload.component.css */ "./src/app/app-knowledge-base/video-upload/video-upload.component.css")).default]
    })
], VideoUploadComponent);



/***/ })

}]);
//# sourceMappingURL=app-knowledge-base-app-knowledge-base-module-es2015.daca45e9781f9dcb38f4.js.map