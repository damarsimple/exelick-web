import { getFilesFromPath, Web3Storage } from "web3.storage";

function makeStorageClient() {
    return new Web3Storage({
        token: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJkaWQ6ZXRocjoweEIxOENiYzg0ZjVEYUI1MGUwZUQ0OGQ4NTZGQTQ2ZmM1MTcyNDJFNjEiLCJpc3MiOiJ3ZWIzLXN0b3JhZ2UiLCJpYXQiOjE2MjkzMDQwMzg0MzIsIm5hbWUiOiJ0cnktdG9rZW4ifQ.ehqUz7sbGtM1CW2OsCLHU6tlN2-OsV6Pjp60XZ0JDyI",
    });
}

async function getFiles(path) {
    const files = await getFilesFromPath(path);
    // console.log(`read ${files.length} file(s) from ${path}`);
    return files;
}

async function storeFiles(files) {
    const client = makeStorageClient();
    const cid = await client.put(files);
    // console.log("stored files with cid:", cid);
    return cid;
}

const path = process.argv.slice(2)[0];

console.log(path);

const files = await getFiles(path);

storeFiles(files)
    .then((e) => {
        console.log(
            JSON.stringify({
                status: true,
                message: "success with cid of " + e,
                cid: e,
            })
        );
    })
    .catch((e) => {
        console.log(
            JSON.stringify({
                status: false,
                message: "failed with reason of " + e,
            })
        );
    });
