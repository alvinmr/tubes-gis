'use strict';
const fs = require('fs');
let rawdata = fs.readFileSync('gadm41_IDN_4.json');
let desa = JSON.parse(rawdata);
// console.log(desa['features'].length);
desa['features'].forEach((d) => {
  // console.log(d);
  if (d.properties.NAME_1 === 'Bali') {
    const data = {
      type: "Feature",
      properties: { provinsi: d.properties.NAME_1.replace(/([a-z])([A-Z])/g, '$1 $2'), kabupaten: d.properties.NAME_2.replace(/([a-z])([A-Z])/g, '$1 $2'), kecamatan: d.properties.NAME_3.replace(/([a-z])([A-Z])/g, '$1 $2'), desa: d.properties.NAME_4.replace(/([a-z])([A-Z])/g, '$1 $2') },
      geometry: { type: "MultiPolygon", coordinates: d.geometry.coordinates }
    };
    const fileName = `public/desa/${d.properties.NAME_4.replace(/([a-z])([A-Z])/g, '$1_$2')}.json`;
    fs.writeFileSync(fileName, JSON.stringify(data));
  }
});