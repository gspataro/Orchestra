import { defineConfig } from "vite";
import * as path from 'path';
import tailwindcss from "@tailwindcss/vite";
import { exec } from 'child_process';

function gspataro() {
    return {
        name: 'gspataro-run-build',
        closeBundle() {
            exec('./gspataro.php build --view-only', (err, stdout, stderr) => {
                if (!process.argv.includes('--watch')) {
                    return;
                }

                if (err) {
                    console.error(`Build error: ${err.message}`);
                }

                if (stderr) {
                    console.error(`stderr: ${err}`);
                }

                console.log(`stdout: ${stdout}`);
            });
        }
    };
}

export default defineConfig({
    base: '/assets/',
    build: {
        outDir: path.resolve(__dirname, 'output/assets'),
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: [
                path.resolve(__dirname, 'contents/assets/css/ui.css'),
                path.resolve(__dirname, 'contents/assets/js/main.js')
            ],
            output: {
                entryFileNames: `[name]-[hash].js`,
                chunkFileNames: `[name]-[hash].js`,
                assetFileNames: `[name]-[hash].[ext]`,
            }
        }
    },
    plugins: [
        tailwindcss(),
        gspataro()
    ]
});
