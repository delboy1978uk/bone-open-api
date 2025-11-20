import {defineConfig} from 'orval';

export default defineConfig({
    'api': {
        input: './data/docs/openapi.yaml',
        output: './api-client.ts',
    },
});
