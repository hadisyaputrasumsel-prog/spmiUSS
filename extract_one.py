import os
import sys
from pypdf import PdfReader
sys.stdout.reconfigure(encoding='utf-8')

pdf_path = r'E:\LPMA\SPMI\USS\Pendidikan-Standar Luaran\1. Standar-Kompetensi-Lulusan.pdf'
out_file = r'c:\laragon\www\AmiraUSS\storage\app\std_kompetensi.txt'

with open(out_file, 'w', encoding='utf-8') as f:
    try:
        reader = PdfReader(pdf_path)
        for i, page in enumerate(reader.pages):
            text = page.extract_text()
            if text:
                f.write(text + '\n')
    except Exception as e:
        f.write(f'Error reading: {e}\n')

print(f'Done')
