/* EPROC application scripts — vendor libraries are loaded before this file. */
(function () {
  "use strict";

  function initTable(selector, options) {
    if (typeof DataTable !== "undefined" && document.querySelector(selector))
      new DataTable(selector, options);
  }

  const commonLanguage = {
    processing: "Memuat data...",
    search: "Cari:",
    lengthMenu: "Tampilkan _MENU_",
    info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
    infoEmpty: "Belum ada data",
    zeroRecords: "Data tidak ditemukan",
    paginate: {
      first: "Awal",
      last: "Akhir",
      next: "Berikutnya",
      previous: "Sebelumnya",
    },
  };
  const responsiveColumns = function (columns) {
    return [
      {
        data: null,
        defaultContent: "",
        className: "dtr-control",
        responsivePriority: 1,
        orderable: false,
        searchable: false,
      },
      {
        data: null,
        className: "text-nowrap",
        responsivePriority: 2,
        orderable: false,
        searchable: false,
        render: (data, type, row, meta) =>
          meta.row + meta.settings._iDisplayStart + 1,
      },
    ].concat(columns);
  };
  const dataTableBase = function (url, columns, order, emptyTable) {
    return {
      serverSide: true,
      processing: true,
      responsive: { details: { type: "column", target: 0 } },
      ajax: {
        url: url,
        type: "GET",
        error: (xhr, textStatus, errorThrown) =>
          console.error(
            "Gagal memuat data:",
            xhr.status,
            textStatus,
            errorThrown,
            xhr.responseText,
          ),
      },
      columns: responsiveColumns(columns),
      order: order,
      pageLength: 10,
      language: Object.assign({}, commonLanguage, { emptyTable: emptyTable }),
    };
  };

  initTable(
    "#companies-table",
    dataTableBase(
      window.eprocUrls?.companies || "companies/datatable",
      [
        { data: "name", responsivePriority: 3 },
        { data: "pic_name", responsivePriority: 10 },
        { data: "phone", responsivePriority: 10 },
        {
          data: "actions",
          responsivePriority: 1,
          orderable: false,
          searchable: false,
        },
      ],
      [[2, "asc"]],
      "Belum ada perusahaan.",
    ),
  );
  initTable(
    "#products-table",
    dataTableBase(
      window.eprocUrls?.products || "products/datatable",
      [
        {
          data: "media",
          responsivePriority: 10,
          orderable: false,
          searchable: false,
        },
        { data: "product", responsivePriority: 3 },
        { data: "cost_price", responsivePriority: 10 },
        { data: "selling_price", responsivePriority: 10 },
        { data: "store", responsivePriority: 10 },
        {
          data: "actions",
          responsivePriority: 1,
          orderable: false,
          searchable: false,
        },
      ],
      [[3, "asc"]],
      "Belum ada produk.",
    ),
  );
  initTable(
    "#quotations-table",
    dataTableBase(
      window.eprocUrls?.quotations || "quotations/datatable",
      [
        { data: "quotation_no", responsivePriority: 3 },
        { data: "company_name", responsivePriority: 10 },
        { data: "title", responsivePriority: 4 },
        { data: "grand_total", responsivePriority: 10 },
        { data: "status", responsivePriority: 10 },
        { data: "created_at", responsivePriority: 10 },
        {
          data: "actions",
          responsivePriority: 1,
          orderable: false,
          searchable: false,
        },
      ],
      [[7, "desc"]],
      "Belum ada penawaran.",
    ),
  );
  initTable(
    "#recent-quotations-table",
    Object.assign(
      dataTableBase(
        window.eprocUrls?.quotations || "quotations/datatable",
        [
          { data: "quotation_no", responsivePriority: 3 },
          { data: "title", responsivePriority: 4 },
          { data: "status", responsivePriority: 10 },
          { data: "created_at", responsivePriority: 10 },
        ],
        [[5, "desc"]],
        "Belum ada penawaran.",
      ),
      {
        pageLength: 5,
        lengthChange: false,
        searching: false,
        language: {
          processing: "Memuat data...",
          info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
          infoEmpty: "Belum ada data",
          zeroRecords: "Belum ada penawaran.",
          emptyTable: "Belum ada penawaran.",
          paginate: { next: "Berikutnya", previous: "Sebelumnya" },
        },
      },
    ),
  );

  window.togglePassword = function (button) {
    const input = button.previousElementSibling;
    if (!input) return;
    input.type = input.type === "password" ? "text" : "password";
    const icon = button.querySelector("i");
    if (icon) icon.classList.toggle("bi-eye-slash");
  };

  window.addSpec = function () {
    const source = document.querySelector(".spec-row");
    const target = document.querySelector("#specifications");
    if (!source || !target) return;
    const row = source.cloneNode(true);
    row.querySelectorAll("input").forEach((input) => (input.value = ""));
    target.appendChild(row);
  };

  window.autoFillCompany = function (select) {
    const company = (window.companiesData || []).find(
      (c) => c.id == select.value,
    );
    const nameInput = document.querySelector('[data-auto-field="customer_name"]');
    const addressInput = document.querySelector('[data-auto-field="customer_address"]');
    const nameValue = document.querySelector('[name="customer_name"]');
    const addressValue = document.querySelector('[name="customer_address"]');
    if (!nameInput || !addressInput || !nameValue || !addressValue) return;
    if (company) {
      nameInput.value = company.name || "";
      addressInput.value = company.address || "";
      nameValue.value = nameInput.value;
      addressValue.value = addressInput.value;
      document.querySelector('[name="customer_phone"]').value =
        company.phone || "";
      document.querySelector('[name="attention"]').value =
        company.pic_name || "";
      nameInput.disabled = true;
      addressInput.disabled = true;
    } else {
      nameInput.disabled = true;
      addressInput.disabled = true;
      nameInput.value = "";
      addressInput.value = "";
      nameValue.value = "";
      addressValue.value = "";
    }
  };
  window.autoFillProduct = function (select) {
    const product = (window.productsData || []).find(
      (p) => p.id == select.value,
    );
    const row = select.closest("tr");
    if (!product || !row) return;
    row.querySelector('[name$="[description]"]').value = "";
    row.querySelector('[name$="[unit_price]"]').value =
      product.selling_price || 0;
    const image = row.querySelector(".product-thumb");
    if (image) {
      image.src = product.image_path || "";
      image.style.display = product.image_path ? "block" : "none";
    }
  };
  window.renumberItems = function () {
    document
      .querySelectorAll("#items tbody .item-row-number")
      .forEach((cell, index) => (cell.textContent = index + 1));
  };
  window.addItem = function () {
    const last = document.querySelector("#items tbody tr:last-child");
    const target = document.querySelector("#items tbody");
    if (!last || !target) return;
    const row = last.cloneNode(true);
    const next = Number(window.quotationItemCount || 0);
    row
      .querySelectorAll("[name]")
      .forEach(
        (element) =>
          (element.name = element.name.replace(
            /items\[\d+\]/,
            "items[" + next + "]",
          )),
      );
    row
      .querySelectorAll("input")
      .forEach(
        (input) => (input.value = input.name.includes("quantity") ? 1 : 0),
      );
    const image = row.querySelector(".product-thumb");
    if (image) image.style.display = "none";
    target.appendChild(row);
    window.quotationItemCount = next + 1;
    window.renumberItems();
  };
  window.removeItem = function (button) {
    const rows = document.querySelectorAll("#items tbody tr");
    const row = button.closest("tr");
    if (!row) return;
    if (rows.length > 1) row.remove();
    else {
      row.querySelectorAll("input").forEach((input) => (input.value = ""));
      row.querySelector("select").value = "";
      const image = row.querySelector(".product-thumb");
      if (image) image.style.display = "none";
    }
    window.renumberItems();
  };

  document.addEventListener("DOMContentLoaded", function () {
    const quotationForm = document.querySelector("form[data-companies]");
    if (quotationForm) {
      try {
        window.companiesData = JSON.parse(
          quotationForm.dataset.companies || "[]",
        );
        window.productsData = JSON.parse(
          quotationForm.dataset.products || "[]",
        );
      } catch (error) {
        console.error("Konfigurasi quotation tidak valid:", error);
        window.companiesData = [];
        window.productsData = [];
      }
      window.quotationItemCount = Number(quotationForm.dataset.itemCount || 0);
    }
    const companySelect = document.querySelector('[name="company_id"]');
    if (companySelect && companySelect.value)
      window.autoFillCompany(companySelect);

    document.addEventListener("click", function (event) {
      const control = event.target.closest("[data-action]");
      if (!control) return;
      const action = control.dataset.action;
      if (action === "toggle-password") window.togglePassword(control);
      if (action === "add-spec") window.addSpec();
      if (action === "remove-spec") control.closest(".spec-row")?.remove();
      if (action === "add-item") window.addItem();
      if (action === "remove-item") window.removeItem(control);
    });

    document.addEventListener("change", function (event) {
      const control = event.target.closest("[data-action]");
      if (!control) return;
      if (control.dataset.action === "company-change")
        window.autoFillCompany(control);
      if (control.dataset.action === "product-change")
        window.autoFillProduct(control);
    });
  });
})();
