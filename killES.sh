#！/bin/sh
#杀死所有的es进程

PROCESS=`ps -ef|grep EasySwoole|grep -v grep|grep -v PPID|awk '{ print $2}'`
for i in $PROCESS
do
  echo "Kill the EasySwoole process [ $i ]"
  kill -9 $i
done
