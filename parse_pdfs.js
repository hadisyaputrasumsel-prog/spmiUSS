const fs = require('fs');
const pdf = require('pdf-parse');
const path = 'E:\\LPMA\\AMI\\UIGM\\';
const files = [
  'BAP Teknik Geomatika.pdf',
  'LAPORAN TINDAK LANJUT (LTL) AMI KIMIA 2023-2024 V.1.pdf',
  'Laporan AMI Kimia.pdf',
  'Rencana Tindaklanjut AMI Kimia.pdf',
  'SS E-SPMI.pdf',
  'Sistem Penjamin Mutu Internal_UIGM3.pdf'
];

async function readPdfs() {
    for (let file of files) {
        console.log(`\n=================== FILE: ${file} ===================`);
        try {
            let dataBuffer = fs.readFileSync(path + file);
            let data = await pdf(dataBuffer);
            // Limit to first 2500 characters to prevent overflow
            console.log(data.text.substring(0, 2500));
        } catch (e) {
            console.log("Error reading " + file + " - " + e.message);
        }
    }
}
readPdfs();
