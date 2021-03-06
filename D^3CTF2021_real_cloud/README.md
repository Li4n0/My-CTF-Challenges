# 环境搭建

题目架构如下：

![image-20210311215422732](.images/image-20210311215422732.png)

## 下载配置文件
clone仓库或使用[download-directory](https://download-directory.github.io/)单独下载本题目文件夹

**注意：** 如果使用[download-directory](https://download-directory.github.io/)下载的该题目文件夹，则需要手动下载 oss 文件夹内的`minio`和`mc`, `k8s/code`文件夹内的`d3cloud-1.0-SNAPSHOT-jar-with-dependencies.jar` 文件。因为`download-directory` 不支持 `git lfs`

## 搭建k8s集群

使用[kubeadm](https://kubernetes.io/docs/setup/production-environment/tools/kubeadm/create-cluster-kubeadm/)搭建 1 node 1 master 的集群、网络插件使用`flannel` 即可

## 部署Serverless

1. 安装 [Fission CLI](https://docs.fission.io/docs/installation/#install-fission-cli)

2. 初始化Serverless

   ```bash
   cd k8s
   kubectl apply -f fission-all-1.11.2.yaml
   kubectl apply -f nginx.yaml
   kubectl apply -f flag.yaml
   
   sh initFission.sh
   ```

3. 解析域名: **fn10050213.serverless.cloud.yourdomain** 到 `k8s node` 的机器IP

## 部署OSS

1. 修改`oss/Caddyfile`，将`d3ctf.io` 替换成`yourdomain`，并添加对应的解析记录
2. 直接使用`docker-compose`启动

## 部署前端

1. 将`index.html` 内的`d3ctf.io`替换为`yourdomain`
2. 启动HTTP服务器

