const print = () => {
    let doc = document.getElementById('print-section').innerHTML;
    const originalContent = document.body.innerHTML;
    document.body.innerHTML = doc;
    window.print();
    document.body.innerHTML = originalContent;
}

const convertToImage = (id,name) => {
    html2canvas(document.getElementById(id)).then(function(canvas) {
        var img = canvas.toDataURL('image/png');
        var downloadLink = document.createElement('a');
        downloadLink.href = img;
        downloadLink.download = name; // Set a default filename for the download
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    });
}
