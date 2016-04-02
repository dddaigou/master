#!/bin/bash
echo $ProjectDir
if [ -z "`kubectl get rc ${ProjectName} -t {{.status.replicas}} 2>/dev/null`" ]
then
(cat<<SUB
apiVersion: v1
kind: ReplicationController
metadata:
  name: ${ProjectName}
spec:
  replicas: ${replicas}
  selector:
    name: ${ProjectName}
  template:
    metadata:
      labels:
        name: ${ProjectName}
    spec:
      containers:
      - name: ${ProjectName}-php
        image: ${PHPIMAGE}
        volumeMounts:
        - name: htdocs
          mountPath: ${HtdocsDir}/www
        - name: upload
          mountPath: ${ProjectUploadDir}
        - name: runtime
          mountPath: ${ProjectRuntimeDir}
        - name: localtime
          mountPath: /etc/localtime
      - name: ${ProjectName}-nginx
        image: ${NGINXIMAGE}
        ports:
        - containerPort: 80
        volumeMounts:
        - name: nginx
          mountPath: /usr/local/nginx/conf
        - name: htdocs
          mountPath: ${HtdocsDir}/www
        - name: upload
          mountPath: ${ProjectUploadDir}
        - name: runtime
          mountPath: ${ProjectRuntimeDir}
        - name: localtime
          mountPath: /etc/localtime
      volumes:
      - name: localtime
        hostPath:
            path: /etc/localtime
      - name: htdocs
        hostPath:
            path: ${ProjectDir}
      - name: upload
        hostPath:
            path: ${UploadDir}
      - name: runtime
        hostPath:
            path: ${RuntimeDir}
      - name: nginx
        hostPath:
            path: ${ProjectDir}/_gitlabci/conf
      restartPolicy: Always
SUB
)| kubectl create -f -
else
    cd ${ProjectDir}
    git pull
fi