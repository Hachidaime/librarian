$(document).ready(function () {
  $.fn.bstooltip = $.fn.tooltip.noConflict()
  $('[data-toggle="tooltip"]').bstooltip()
})

let yesText = /* html */ `<i class="fas fa-check-circle text-success"></i>`
let noText = /* html */ `<i class="fas fa-times-circle text-danger"></i>`

/**
 * @description fungsi ini untuk menampilkan flash data
 * @function flash()
 * @param {string} title
 * @param {string} icon
 */
let flash = (title, icon) => {
  Swal.fire({
    toast: true,
    position: 'top',
    icon: icon,
    title: title,
    showConfirmButton: false,
    timer: 3000,
  })
}

/**
 * @description fungsi ini untuk menampilkan error form validation
 * @function showErrorMessage()
 * @param {string} id is field id
 * @param {string} message is error message
 */
let showErrorMessage = (id, message) => {
  $(`#${id}`).addClass('is-invalid').next().html(message)
}

/**
 * @description fungsi ini untuk clear error form validation
 * @function clearErrorMessage()
 */
let clearErrorMessage = () => {
  // Todo: clear error message
  $('.form-control, .custom-select, .btn-group-toggle, .input-file')
    .removeClass('is-invalid')
    .next('.invalid-feedback')
    .html('')
}

/**
 * @description fungsi ini menampilkan tooltip pada form input
 * @function formTooltip
 *
 * @param {string} id id field input
 * @param {string} color warna background tooltip - menggunakan Bootstrap color
 * @param {string} placement posisi tooltip - auto|top|right|bottom|left
 */
let formTooltip = (id, color = 'warning', placement = 'top') => {
  let title = $(`#${id}`).data('title') ?? 'Tooltip'
  $(`#${id}`).bstooltip({
    trigger: 'focus',
    placement: placement,
    template: /*html*/ `
    <div class="tooltip" role="tooltip">
      <div class="arrow arrow-${placement}-${color}"></div>
      <div class="tooltip-inner bg-gradient-${color} border border-${color}"></div>
    </div>
    `,
    title: title,
  })
}

/**
 * @description fungsi ini akan menghapus data di database berdasarkan id
 * @function deleteData
 *
 * @param {number} id id data
 */
let deleteData = (id) => {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn bg-gradient-danger ml-2',
      cancelButton: 'btn bg-gradient-light',
    },
    buttonsStyling: false,
  })

  swalWithBootstrapButtons
    .fire({
      position: 'top',
      title: 'Apakah Anda yakin?',
      text: 'Anda tidak akan dapat mengembalikan data ini!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal',
      reverseButtons: true,
    })
    .then((result) => {
      if (result.value) {
        let data = `id=${id}`
        let url = `${MAIN_URL}/remove`
        $.post(
          url,
          data,
          (res) => {
            if (res.success) {
              window.location = MAIN_URL
            } else {
              flash(res.msg, 'error')
            }
          },
          'JSON'
        )
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        flash('Hapus data batal.', 'error')
      }
    })
}

let upload = (param) => {
  /**
   * * Mendefinisikan variable
   */
  let input = $(param)
  let id = input.data('id') // ? field id
  let files = input[0].files[0]
  let accept = input.attr('accept')
  let url = `${BASE_URL}/file/upload`

  /**
   * * Mendefinisikan Input Data
   */
  let fd = new FormData()
  fd.append('file', files)
  fd.append('accept', accept)

  // ToDO: Ajax Request
  $.ajax({
    url: url,
    type: 'post',
    data: fd,
    dataType: 'json',
    contentType: false,
    processData: false,
    success: function (data) {
      // TODO: Menampilkan Alert
      flash(data.alert.message, data.alert.type)

      // TODO: Cek Status Upload
      if (data.alert.type == 'warning') {
        // ? Upload Berhasil

        // TODO: Menampilkan preview gambar
        showPreview(id, data)

        // TODO: Menampilkan link download dari file yang diupload
        showFileAction(id, data)

        // TODO: Set input value untuk file upload
        $(`#${id}`).val(data.filename)

        const re = /(?:\.([^.]+))?$/

        String.prototype.trunc =
          String.prototype.trunc ||
          function (n) {
            return this.length > n
              ? this.substr(0, n - 1) + ' &hellip; ' + re.exec(this)[0]
              : this
          }

        $(`#${id}`)
          .siblings('.input-group')
          .find('.custom-file-label')
          .html(data.filename.trunc(15))
      } else if (data.alert.type == 'error') {
        // ! upload gagal
        $(`#${id}`).siblings('.input-group').find('.custom-file-label').text('')
      }
    },
  })
}

let download = (filepath) => {
  const url = `${BASE_URL}/${filepath}`
  let hiddenIFrameID = 'hiddenDownloader',
    iframe = document.getElementById(hiddenIFrameID)
  if (iframe === null) {
    iframe = document.createElement('iframe')
    iframe.id = hiddenIFrameID
    iframe.style.display = 'none'
    document.body.appendChild(iframe)
  }
  iframe.src = url
}

let showPreview = (id, data) => {
  let preview = $(`#preview_${id}`) // ? file preview

  // TODO: Menampilkan preview gambar
  preview.show()
  preview.find('img').attr({
    src: data.source,
    alt: data.filename,
  })
  preview.find('a').attr({
    href: data.source,
  })
}

let showFileAction = (id, data) => {
  let fileAction = $(`#file_action_${id}`) // ? file action: download

  // TODO: Menampilkan link download dari file yang diupload
  fileAction.show()
  fileAction.find('.filename').text(data.filename)
  fileAction.find('a').attr('href', data.source)
}

/**
 * @description Fungsi ini akan membuat pagination
 * @function createPagination
 *
 * @param {*} paging Pagination info
 * @param {*} pagination_id ID Pagination
 */
let createPagination = (page, paging, pagination_id) => {
  // Total Data Rows
  let totalRows = document.querySelector(`#${pagination_id} #totalRows`)
  totalRows.innerHTML = paging.total ?? 0

  // Previous Button
  let previousBtn = document.querySelector(`#${pagination_id} #previousBtn`)
  previousBtn.disabled = true
  delete previousBtn.dataset.id
  if (paging.prev_page != null && Number(paging.total) > 0) {
    previousBtn.disabled = false
    previousBtn.dataset.id = paging.prev_page
  }

  // Pager Select
  let pageSelect = document.querySelector(`#${pagination_id} #page`)

  while (pageSelect.hasChildNodes()) {
    pageSelect.removeChild(pageSelect.firstChild)
  }

  for (let index = 1; index <= paging.last_page; index++) {
    let pageOption = document.createElement('option')
    pageOption.setAttribute('value', index)
    pageOption.innerHTML = index

    pageSelect.appendChild(pageOption)
  }

  pageSelect.value = page
  // End Pager Select

  // Next Button
  let nextBtn = document.querySelector(`#${pagination_id} #nextBtn`)
  nextBtn.disabled = true
  delete nextBtn.dataset.id
  if (paging.next_page != null && Number(paging.total) > 0) {
    nextBtn.disabled = false
    nextBtn.dataset.id = paging.next_page
  }
}

let camelCase = (str, delimeter = '_') => {
  return str
    .split(delimeter)
    .reduce((a, b) => a + b.charAt(0).toUpperCase() + b.slice(1))
}

let reArrange = (container, currentPage = 1) => {
  let rowNo = 1
  document.querySelectorAll(container).forEach(function (tr) {
    tr.firstChild.innerHTML =
      Number(ROWS_PER_PAGE) * (Number(currentPage) - 1) + rowNo
    rowNo++
  })
}

let createElement = (params) => {
  let element = document.createElement(params.element)

  if (params.id !== undefined) element.id = params.id

  if (params.class !== undefined)
    params.class.forEach((value) => {
      element.classList.add(value)
    })

  if (params.attribute !== undefined)
    Object.entries(params.attribute).forEach(([key, value]) => {
      element.setAttribute(key, value)
    })

  if (params.data !== undefined)
    Object.entries(params.data).forEach(([key, value]) => {
      element.dataset[key] = value
    })

  if (params.children !== undefined)
    params.children.forEach((value) => {
      if (typeof value == 'object' && value != null) element.appendChild(value)
      else element.innerHTML = value
    })

  return element
}
