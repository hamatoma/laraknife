#! /usr/bin/python3
import sys
import json

class JoinWorker:
    def __init__(self) -> None:
        self.all = {}
        
    def error(self, msg: str):
        print(f'+++ {msg}')

    def joinFile(self, filename: str):
        with open(filename, 'r') as fp:
            try:
                tree = json.load(fp)
                for key in tree:
                    node = tree[key]
                    if key in self.all:
                        if isinstance(node, str):
                            if node != self.all[key]:
                                self.error(f'different values: "{key}": "{node}" | "{self.all[key]}"')
                        else:
                            self.error(f'different non string values: "{key}"')
                        continue
                    self.all[key] = node
                print(f'read: {filename}')
            except Exception as exc:
                self.error(f'+++ {exc}')
                raise

    def write(self, filename: str):
        try:
            contents = json.dumps(self.all, sort_keys=True, indent=1)
            with open(filename, 'w') as fp:
                fp.write(contents)
                print(f'written: {filename}')
        except Exception as exc:
            self.error(f'{exc}')
           

def usage(msg: str):
    print(f'''Usage joinjson INPUT1 INPUT2 ... OUTPUT
Example joinjson /etc/data.json /home/joe/data.json /tmp/common_data
+++ {msg}
''')
def main(argv):
    if len(argv) < 3:
        usage('missing arguments')    
    else:
        output = argv[len(argv) - 1]
        inputs = argv[0:len(argv) - 1]
        worker = JoinWorker()
        try:
            for input in inputs:
                worker.joinFile(input)
            worker.write(output)
        except:
            pass
    
def test():
    main(
        ['data/example1.json', 
        'data/example2.json',
        '/tmp/out.json'
          ])
if __name__ == "__main__":
    argv = sys.argv
    if len(argv) > 1 and argv[1] == '--test':
        test()
    else:
        sys.exit(main(argv[1:]))                 
