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

  function initConfirmationModal() {
    const modalElement = document.querySelector("#appConfirmModal");
    if (!modalElement || typeof bootstrap === "undefined") return;

    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
    const title = modalElement.querySelector("[data-confirm-title]");
    const message = modalElement.querySelector("[data-confirm-message]");
    const icon = modalElement.querySelector("[data-confirm-icon]");
    const submit = modalElement.querySelector("[data-confirm-submit]");
    let pendingForm = null;

    const variantIcons = {
      primary: "bi-send",
      success: "bi-check2-circle",
      warning: "bi-clock-history",
      danger: "bi-trash3",
    };

    const open = (form) => {
      pendingForm = form;
      const variant = form.dataset.confirmVariant || "primary";
      title.textContent = form.dataset.confirmTitle || "Konfirmasi";
      message.textContent = form.dataset.confirmMessage || "Apakah Anda yakin ingin melanjutkan tindakan ini?";
      submit.textContent = form.dataset.confirmLabel || "Konfirmasi";
      submit.className = `btn btn-${variant}`;
      icon.className = `confirm-modal-icon confirm-modal-icon-${variant}`;
      icon.innerHTML = `<i class="bi ${variantIcons[variant] || "bi-question-lg"}"></i>`;
      modal.show();
    };

    document.addEventListener("submit", (event) => {
      const form = event.target.closest("form[data-confirm]");
      if (!form) return;
      if (form.dataset.confirmed === "true") {
        delete form.dataset.confirmed;
        return;
      }
      event.preventDefault();
      open(form);
    });

    submit.addEventListener("click", () => {
      if (!pendingForm) return;
      const form = pendingForm;
      pendingForm = null;
      form.dataset.confirmed = "true";
      modal.hide();
      form.requestSubmit();
    });

    modalElement.addEventListener("hidden.bs.modal", () => {
      pendingForm = null;
    });
  }

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

  window.updateQuotationNumberPreview = function () {
    const preview = document.querySelector("[data-quotation-preview]");
    const companySelect = document.querySelector('[name="company_id"]');
    const dateInput = document.querySelector('[name="issue_date"]');
    if (!preview || preview.dataset.quotationPreview === "static" || !companySelect || !dateInput) return;
    const company = (window.companiesData || []).find(
      (item) => item.id == companySelect.value,
    );
    const selectedDate = dateInput.value;
    if (!company || !selectedDate) {
      preview.value = "";
      return;
    }
    const parts = selectedDate.split("-").map(Number);
    const monthRoman = [
      "I",
      "II",
      "III",
      "IV",
      "V",
      "VI",
      "VII",
      "VIII",
      "IX",
      "X",
      "XI",
      "XII",
    ];
    const companyName = (company.name || "")
      .replace(/^PT\.?\s*/i, "")
      .trim();
    const initials = companyName
      .split(/\s+/)
      .filter(Boolean)
      .map((word) => word.charAt(0).toUpperCase())
      .join("");
    const prefix = (company.quotation_prefix || "CCIP").trim().toUpperCase();
    const code =
      (company.quotation_code || "").trim().toUpperCase() || `TRE-${initials || "GEN"}`;
    const sequence = Number(company.quotation_sequence || 0) + 1;
    preview.value = `${prefix}${String(sequence).padStart(3, "0")}${String(parts[2]).padStart(2, "0")}/${code}/${monthRoman[parts[1] - 1]}/${parts[0]}`;
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
    if (!row) return;
    const image = row.querySelector(".product-thumb");
    const placeholder = row.querySelector(".product-image-placeholder");
    const imagePath = product && (product.image_path || product.image_url);
    if (image) {
      image.src = imagePath || "";
      image.alt = product ? `Gambar ${product.name || "produk"}` : "Gambar produk";
      image.hidden = !imagePath;
    }
    if (placeholder) placeholder.hidden = Boolean(imagePath);
    if (!product) return;
    row.querySelector('[name$="[description]"]').value = "";
    row.querySelector('[name$="[unit_price]"]').value =
      product.selling_price || 0;
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
    const select = row.querySelector('[name$="[product_id]"]');
    if (select) select.value = "";
    row.querySelectorAll("input").forEach((input) => {
      if (input.name.includes("quantity")) input.value = 1;
      else if (input.name.includes("unit]")) input.value = "pcs";
      else input.value = "";
    });
    const image = row.querySelector(".product-thumb");
    if (image) {
      image.src = "";
      image.hidden = true;
      image.alt = "Gambar produk";
    }
    const placeholder = row.querySelector(".product-image-placeholder");
    if (placeholder) placeholder.hidden = false;
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
      if (image) {
        image.src = "";
        image.hidden = true;
      }
      const placeholder = row.querySelector(".product-image-placeholder");
      if (placeholder) placeholder.hidden = false;
    }
    window.renumberItems();
  };
  window.updateNegotiationTotals = function () {
    const form = document.querySelector("[data-negotiation-editor]");
    if (!form) return;
    let subtotal = 0;
    form.querySelectorAll(".negotiation-item-row").forEach((row, index) => {
      const quantity = Number(row.querySelector('[name$="[quantity]"]')?.value || 0);
      const price = Number(row.querySelector('[name$="[unit_price]"]')?.value || 0);
      const discount = Number(row.querySelector('[name$="[discount_percent]"]')?.value || 0);
      const lineTotal = Math.max(0, quantity * price * (1 - discount / 100));
      subtotal += lineTotal;
      const line = row.querySelector("[data-negotiation-line-total]");
      if (line) line.textContent = `Rp ${Math.round(lineTotal).toLocaleString("id-ID")}`;
      const number = row.querySelector(".negotiation-row-number");
      if (number) number.textContent = index + 1;
    });
    const taxPercent = Number(form.querySelector('[name="tax_percent"]')?.value || 0);
    const tax = subtotal * taxPercent / 100;
    const format = (value) => `Rp ${Math.round(value).toLocaleString("id-ID")}`;
    const subtotalNode = form.querySelector("[data-negotiation-subtotal]");
    const taxNode = form.querySelector("[data-negotiation-tax]");
    const grandNode = form.querySelector("[data-negotiation-grand-total]");
    if (subtotalNode) subtotalNode.textContent = format(subtotal);
    if (taxNode) taxNode.textContent = format(tax);
    if (grandNode) grandNode.textContent = format(subtotal + tax);
  };
  window.addNegotiationItem = function () {
    const target = document.querySelector("#negotiation-items tbody");
    const last = target?.querySelector("tr:last-child");
    if (!target || !last) return;
    const row = last.cloneNode(true);
    const next = target.querySelectorAll("tr").length;
    row.querySelectorAll("[name]").forEach((element) => {
      element.name = element.name.replace(/items\[\d+\]/, `items[${next}]`);
      if (element.tagName === "SELECT") element.value = "";
      else if (element.name.includes("[quantity]")) element.value = 1;
      else if (element.name.includes("[unit]")) element.value = "pcs";
      else element.value = "";
    });
    const total = row.querySelector("[data-negotiation-line-total]");
    if (total) total.textContent = "Rp 0";
    target.appendChild(row);
    window.updateNegotiationTotals();
  };
  window.removeNegotiationItem = function (button) {
    const target = document.querySelector("#negotiation-items tbody");
    const row = button.closest("tr");
    if (!target || !row) return;
    const rows = target.querySelectorAll("tr");
    if (rows.length > 1) row.remove();
    else row.querySelectorAll("input").forEach((input) => (input.value = ""));
    window.updateNegotiationTotals();
  };
  window.autoFillNegotiationProduct = function (select) {
    const option = select.options[select.selectedIndex];
    const price = option?.dataset.price;
    const row = select.closest("tr");
    const priceInput = row?.querySelector('[name$="[unit_price]"]');
    if (priceInput && price) priceInput.value = price;
    window.updateNegotiationTotals();
  };

  document.addEventListener("DOMContentLoaded", function () {
    initConfirmationModal();
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
      if (action === "add-negotiation-item") window.addNegotiationItem();
      if (action === "remove-negotiation-item") window.removeNegotiationItem(control);
    });

    document.addEventListener("change", function (event) {
      const control = event.target.closest("[data-action]");
      if (!control) return;
      if (control.dataset.action === "company-change")
        window.autoFillCompany(control), window.updateQuotationNumberPreview();
      if (control.dataset.action === "product-change")
        window.autoFillProduct(control);
      if (control.dataset.action === "negotiation-product-change")
        window.autoFillNegotiationProduct(control);
    });
    document.querySelectorAll("[data-negotiation-editor] .negotiation-number, [data-negotiation-editor] [name='tax_percent']").forEach((input) => input.addEventListener("input", window.updateNegotiationTotals));
    const quotationDate = document.querySelector('[name="issue_date"]');
    if (quotationDate) {
      quotationDate.addEventListener("change", window.updateQuotationNumberPreview);
    }
    window.updateQuotationNumberPreview();
    window.updateNegotiationTotals();
  });
})();
