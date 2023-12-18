const sass = require('sass');
const fs = require('fs');
const path = require('path');

const inputFile = process.argv[2];
const isProduction = process.argv.includes('--prod');

let outputFile;
let sassOptions = {
    style: isProduction ? 'compressed' : 'expanded'
};

if (inputFile.startsWith('blocks/')) {
    outputFile = inputFile.replace('.scss', '.css');
} else if (inputFile.startsWith('assets/scss/')) {
    // Compile directly to assets/css with the same filename
    outputFile = 'assets/css/' + path.basename(inputFile, '.scss') + '.css';
}

sass.compileAsync(inputFile, sassOptions)
    .then(result => {
        fs.mkdirSync(path.dirname(outputFile), { recursive: true });
        fs.writeFileSync(outputFile, result.css);
        // If production, skip writing source maps
        if (!isProduction && result.sourceMap) {
            fs.writeFileSync(outputFile + '.map', result.sourceMap);
        }
    })
    .catch(error => {
        console.error(error.toString());
    });
