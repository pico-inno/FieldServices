const print = () => {
    let doc = document.getElementById('print-section').innerHTML;
    const originalContent = document.body.innerHTML;
    document.body.innerHTML = doc;
    window.print();
    document.body.innerHTML = originalContent;
}

const convertToImage = (element, name) => {
    html2canvas(element, { useCORS: true, allowTaint: true }).then(function (canvas) {
        var img = canvas.toDataURL('image/png');
        var downloadLink = document.createElement('a');
        downloadLink.href = img;

        downloadLink.download = name; // Set a default filename for the download
        document.body.appendChild(downloadLink);
        downloadLink.click();

        Swal.fire({
            title: 'Image downloaded!',
            type: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Got it!'
        });
        document.body.removeChild(downloadLink);
    });
};
