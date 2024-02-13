#! /usr/bin/python3
import os.path
import sys
import re
import argparse

class ReplaceEngine:
    def __init__(self, separator: str, comment: str) -> None:
        self._separator = separator
        self._comment = comment if comment != '' else None
        self._replacements = {}
        self._filename = None
        self._lineNo = 0
        self._hits = 0
        self._countFiles = 0
        self._countHitFiles = 0
        self._countDirs = 0
        self._nodeRegExpr = None
        self.errors = 0
      
    def error(self, msg: str) -> None:
        if self._filename is None:
            print(f'+++ {msg}')
        else:
            print(f'+++ {self._filename}-{self._lineNo}: {msg}')
            
    def log(self, msg: str):
        print(msg)
    
    def logStatistics(self):
        self.log(f'dirs: {self._countDirs} files: {self._countHitFiles} from {self._countFiles} hits: {self._hits}')
        
    def oneFile(self, filename: str) -> None:
        self._countFiles += 1
        self._filename = filename
        lines = []
        hits = 0
        with open(filename, 'r') as fp:
            self._lineNo = 0
            for line in fp:
                self._lineNo += 1
                for pair in self._replacements.items():
                    hits2 = line.count(pair[0])
                    if hits2 > 0:
                        hits += hits2
                        self._hits += hits2
                        line = line.replace(pair[0], pair[1])
                lines.append(line)
        if hits > 0:
            self._countHitFiles += 1
            with open(filename, "w") as fp2:
                data = ''.join(lines)
                fp2.write(data)
            self.log(f'{filename}: {hits} replacement(s)')   
        
    def oneDirectory(self, path: str) -> None:
        self._countDirs += 1
        files = os.listdir(path)
        subdirs = []
        for node in files:
            full = f'{path}/{node}'
            if os.path.isdir(full):
                subdirs.append(node)
            elif self._nodeRegExpr is None or self._nodeRegExpr.match(node):
                self.oneFile(full)
        for node in subdirs:
             full = f'{path}/{node}'
             self.oneDirectory(full)            
            
    def readReplacements(self, filename: str) -> bool:
        rc = False
        self._filename = filename
        with open(filename, 'r') as fp:
            self._lineNo = 0
            for line in fp:
                self._lineNo += 1
                line = line.rstrip('\n\c')
                if line.strip() == '' or (self._comment is not None and line.startswith(self._comment)):
                    continue
                parts = line.split(self._separator)
                if len(parts) == 1:
                    self.error(f'missing separator ({self._separator}): {line}')
                elif len(parts) > 2:
                    self.error(f'too many separators: {line}')
                else:
                    self._replacements[parts[0]] = parts[1]
            rc = self.errors == 0
        self._filename = None
        return rc

    def setPattern(self, pattern: str) -> None:           
        self._nodeRegExpr = None if pattern == '.*' else re.compile(pattern)
        
def usage(msg: str, parser):
    parser.print_help()
    print(f'+++ {msg}')
    sys.exit(1)   

def test():
    base = '/tmp/unittest'
    if not os.path.exists(base):
        os.mkdir(base)
    fnDemo = f'{base}/replacments.demo'
    fnData = f'{base}/replacments.data'
    with open(fnDemo, 'w') as fp:
        fp.write('''line 1
line 2
With a little help from my friends.
''')
        with open(fnData, 'w') as fp:
            fp.write('''line;Line
# comment
f;X
''')
    argv = ['--separator=;', fnData, base, '-f^.*\.demo$']
    return argv
    
def main(argv):
    if len(argv) == 99 or (len(argv) == 1 and argv[0] == '--test'):
        argv = test()
    parser = argparse.ArgumentParser(description='Replaces strings with replacements read from a file in a file or directory.',
                                        usage='''replacestrings.py [<options>] <file-with-replacements> <file-or-directory> [<file-regexpr-pattern>]
examples:
replacestrings --separator=; data/repl.data /home/ws '^.*\.(txt|[ch]pp)$'
replacestrings '--comment=' data/repl.data /home/ws/test.cpp
''')
    parser.add_argument("-s", "--separator", dest="separator", 
                        help="Separates the string from the replacements in <file-with-replacements>. Default: TAB",
                        default='\t')
    parser.add_argument("-c", "--comment", dest="comment", 
                        help="If a line <file-with-replacements> starts with that string the line is ignored. Default: #",
                        default='#')
    parser.add_argument('replacementData', type=str, help="The file with the string-separator-replacement lines")
    parser.add_argument('fileOrDirectory', type=str, help="The file or the directory to process")
    parser.add_argument('-f', '--file-pattern', dest='filePattern', type=str, help="The regular expression specifying the files to to process. Default: .*",
                        default=".*")
    
    # Process arguments
    args = parser.parse_args(argv)
    separator = args.separator
    comment = args.comment
    if separator == '':
        separator = '\t'
    engine = ReplaceEngine(separator, comment)
    fnData = args.replacementData
    fileOrDirectory = args.fileOrDirectory
    
    if not os.path.exists(fnData):
        usage(f'missing {fnData}', parser)
    if not engine.readReplacements(fnData):
        usage(f'error in configuration file: {fnData}', parser)
    else:
        if not os.path.exists(fileOrDirectory):
            usage(f'missing {fileOrDirectory}', parser)
        elif not os.path.isdir(fileOrDirectory):
            engine.oneFile(fileOrDirectory)
        else:
            if len(argv) > 2:
                engine.setPattern(args.filePattern)
                engine.oneDirectory(fileOrDirectory)
                engine.logStatistics()

if __name__ == "__main__":
    main(sys.argv[1:])
