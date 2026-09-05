import PyPDF2

pdf_path = r'C:\laragon\www\AmiraUSS\docs\Sarjana - Matriks Penilaian unggul.pdf'

try:
    with open(pdf_path, 'rb') as file:
        reader = PyPDF2.PdfReader(file)
        text = ""
        for i, page in enumerate(reader.pages):
            text += f"\n--- Page {i+1} ---\n"
            text += page.extract_text()
        print(text)
except Exception as e:
    print(f"Error reading PDF: {e}")
