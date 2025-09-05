document.addEventListener('DOMContentLoaded', function() {
    function updateClock() {
        let now = new Date();
        
        // Konversi ke WITA (UTC+8)
        let utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        let wita = new Date(utc + (8 * 3600000));

        let options = { day: '2-digit', month: 'short', year: 'numeric' };
        let tanggal = wita.toLocaleDateString('id-ID', options);
        
        let jam = String(wita.getHours()).padStart(2, '0');
        let menit = String(wita.getMinutes()).padStart(2, '0');
        let detik = String(wita.getSeconds()).padStart(2, '0');

        document.getElementById('live-clock').textContent = `${tanggal}, ${jam}:${menit}:${detik} WITA`;
    }

    updateClock(); // Jalankan pertama kali
    setInterval(updateClock, 1000); // Update setiap 1 detik
});