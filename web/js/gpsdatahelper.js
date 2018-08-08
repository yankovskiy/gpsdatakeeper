function downloadGpsData(data) {
    let mydata = '';
    if(data.format === 'gpx') {
        mydata = togpx(JSON.parse(data.data));
    } else if (data.format === 'kml') {
        mydata = tokml(JSON.parse(data.data));
    }
    const convertedData = 'application/xml;charset=utf-8,' + encodeURIComponent(mydata);
    const element = document.createElement('a');
    element.setAttribute('href', 'data:' + convertedData);
    element.setAttribute('download', data.fileName + '.' + data.format);
    element.style.display = 'none';
    element.click();
}