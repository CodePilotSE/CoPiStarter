const { exec } = require('child_process');
const { glob } = require('glob');

// Function to compile a single SCSS file
function compileScss(path, isProd) {
    return new Promise((resolve, reject) => {
        const command = `node compile-scss.js ${path}` + (isProd ? ' --prod' : '');
        exec(command, (error, stdout, stderr) => {
            if (error) {
                console.error(`Error compiling ${path}: ${stderr}`);
                reject(error);
            } else {
                console.log(`Compiled ${path}: ${stdout}`);
                resolve();
            }
        });
    });
}

// Replace the callback-style code with async/await
(async () => {
    try {
        const assetsFiles = await glob('assets/scss/*.scss');
        const blocksFiles = await glob('blocks/**/*.scss');

        const allFiles = [...assetsFiles, ...blocksFiles];
        const compilePromises = allFiles.map(file => compileScss(file, process.argv.includes('--prod')));

        await Promise.all(compilePromises);

        console.log('All SCSS files compiled successfully.');
    } catch (error) {
        console.error('Error compiling SCSS files:', error);
    }
})();
