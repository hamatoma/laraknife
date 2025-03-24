#! /usr/bin/python3
import argparse
import sys

def replace_in_file(insert_mark: str, input_file: str, replacement_file: str, output_file: str):
    if output_file is None:
        output_file = input_file
    with open(replacement_file, 'r') as rf:
        replacement_content = rf.read()

    with open(input_file, 'r') as inf:
        content = inf.read()

    updated_content = content.replace(insert_mark, replacement_content)

    with open(output_file, 'w') as outf:
        outf.write(updated_content)

def main(argv):
    parser = argparse.ArgumentParser(description='Replace a given marker in a file with the content of another file.')
    parser.add_argument('--marker', required=True, help='String inside the input file that will be replaced by the content of the replacement file')
    parser.add_argument('input', help='The input file to be processed')
    parser.add_argument('replacement', help='The file containing the replacement content')
    parser.add_argument('--output', help='The output file to save the result: if not provided, the input file will be overwritten')

    args = parser.parse_args(argv);
    replace_in_file(args.marker, args.input, args.replacement, args.output)

if __name__ == "__main__":
    #main(['--marker', '#X#', '/tmp/input.txt', '/tmp/replacement.txt', '--output', '/tmp/output.txt'])
    main(sys.argv)