document.addEventListener('DOMContentLoaded', function () {
    // CREATE: auto-open modal kalau ada error validasi
    var createErrorMarker = document.getElementById('modalCreateErrorMarker');
    if (createErrorMarker) {
      var addEl = document.getElementById('modalTambah');
      if (addEl && window.bootstrap) {
        new bootstrap.Modal(addEl).show();
      }
    }
  
    // EDIT: auto-open modal kalau ada error validasi (pakai marker & old())
    var editErrorMarker = document.getElementById('modalEditErrorMarker');
    if (editErrorMarker) {
      var editEl = document.getElementById('modalEdit');
      var formEl = document.getElementById('formEdit');
      if (editEl && formEl && window.bootstrap) {
        // set action
        formEl.action = "/mahasiswa/" + (editErrorMarker.dataset.id || '');
        // isi field dari marker (old())
        var setVal = function(id, val){
          var el = document.getElementById(id);
          if (el) el.value = val || '';
        };
        setVal('edit_nim',   editErrorMarker.dataset.nim);
        setVal('edit_nama',  editErrorMarker.dataset.nama);
        setVal('edit_jk',    editErrorMarker.dataset.jk);
        setVal('edit_kelas', editErrorMarker.dataset.kelas);
        setVal('edit_uid',   editErrorMarker.dataset.uid);
  
        new bootstrap.Modal(editEl).show();
      }
    }
  
    // EDIT: saat tombol Edit diklik, populate modal dari data-* tombol
    var modalEdit = document.getElementById('modalEdit');
    if (modalEdit) {
      modalEdit.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        if (!button) return;
  
        var form = document.getElementById('formEdit');
        if (form) form.action = "/mahasiswa/" + (button.dataset.id || '');
  
        var setVal = function(id, val){
          var el = document.getElementById(id);
          if (el) el.value = val || '';
        };
        setVal('edit_nim',   button.dataset.nim);
        setVal('edit_nama',  button.dataset.nama);
        setVal('edit_jk',    button.dataset.jk);
        setVal('edit_kelas', button.dataset.kelas);
        setVal('edit_uid',   button.dataset.uid);
      });
    }
  });
  